<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Embarque;
use App\Models\EntradaEmbarque;
use App\Models\EstatusEmbarque;
use App\Models\EstatusEntrada;
use App\Models\EvidenciaEmbarque;
use App\Models\PreRegistroRefaccion;
use App\Models\Refaccion;
use App\Models\TipoEvidencia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Recepción de mercancía: un embarque agrupa entradas de refacciones
 * existentes y pre-registros de refacciones nuevas, con evidencias
 * fotográficas y factura (imagen o PDF). Al aprobarse, las nuevas se dan
 * de alta en el catálogo y las existentes suman stock.
 */
class EmbarqueService
{
    private const DIR_EVIDENCIAS = 'evidenciasVXM/imgGeneralesEmbarque';
    private const DIR_FACTURA_IMG = 'evidenciasVXM/imgFacturaEmbarque';
    private const DIR_FACTURA_PDF = 'evidenciasVXM/pdfFacturaEmbarque';

    public function __construct(private readonly ImageService $imageService)
    {
    }

    public function listar(): Collection
    {
        return Embarque::activo()
            ->with(['proveedor', 'usuarioCrea', 'estatusEmbarque'])
            ->orderByDesc('id_embarque')
            ->get()
            ->map(fn (Embarque $e) => $this->resumen($e));
    }

    public function detalle(int $idEmbarque): array
    {
        $embarque = Embarque::activo()
            ->with(['proveedor', 'usuarioCrea', 'estatusEmbarque'])
            ->findOrFail($idEmbarque);

        $entradas = EntradaEmbarque::activo()
            ->where('id_embarque', $idEmbarque)
            ->whereNotNull('id_refaccion')
            ->with('refaccion.marcaRefaccion', 'refaccion.categoriaRefaccion', 'refaccion.subcategoriaRefaccion', 'refaccion.claseRefaccion')
            ->get()
            ->map(fn (EntradaEmbarque $e) => [
                'id_entrada_embarque' => $e->id_entrada_embarque,
                'id_embarque' => $e->id_embarque,
                'id_refaccion' => $e->id_refaccion,
                's_nombre_refaccion' => $e->refaccion?->s_nombre_refaccion,
                's_marca_refaccion' => $e->refaccion?->marcaRefaccion?->s_marca_refaccion,
                's_categoria_refaccion' => $e->refaccion?->categoriaRefaccion?->s_categoria_refaccion,
                's_subcategoria_refaccion' => $e->refaccion?->subcategoriaRefaccion?->s_subcategoria_refaccion,
                's_clase_refaccion' => $e->refaccion?->claseRefaccion?->s_clase_refaccion,
                's_numero_parte' => $e->refaccion?->s_numero_parte,
                'n_cantidad' => $e->n_cantidad,
                'n_precio_compra' => $e->n_precio_compra,
                's_codigo_barras' => $e->s_codigo_barras,
                'd_fecha_creacion' => $e->d_fecha_creacion,
            ]);

        $pendientes = EntradaEmbarque::activo()
            ->where('id_embarque', $idEmbarque)
            ->whereNotNull('id_pre_registro_refaccion')
            ->with('preRegistroRefaccion.marcaRefaccion', 'preRegistroRefaccion.categoriaRefaccion', 'preRegistroRefaccion.subcategoriaRefaccion', 'preRegistroRefaccion.claseRefaccion', 'preRegistroRefaccion.usuarioCrea')
            ->get()
            ->map(fn (EntradaEmbarque $e) => [
                'id_entrada_embarque' => $e->id_entrada_embarque,
                'id_embarque' => $e->id_embarque,
                'id_pre_registro_refaccion' => $e->id_pre_registro_refaccion,
                'n_cantidad' => $e->n_cantidad,
                'n_precio_compra' => $e->n_precio_compra,
                's_codigo_barras' => $e->s_codigo_barras,
                'd_fecha_creacion' => $e->d_fecha_creacion,
                's_nombre_refaccion' => $e->preRegistroRefaccion?->s_nombre_refaccion,
                's_numero_parte' => $e->preRegistroRefaccion?->s_numero_parte,
                'id_marca_refaccion' => $e->preRegistroRefaccion?->id_marca_refaccion,
                's_marca_refaccion' => $e->preRegistroRefaccion?->marcaRefaccion?->s_marca_refaccion,
                'id_categoria_refaccion' => $e->preRegistroRefaccion?->id_categoria_refaccion,
                's_categoria_refaccion' => $e->preRegistroRefaccion?->categoriaRefaccion?->s_categoria_refaccion,
                'id_subcategoria_refaccion' => $e->preRegistroRefaccion?->id_subcategoria_refaccion,
                's_subcategoria_refaccion' => $e->preRegistroRefaccion?->subcategoriaRefaccion?->s_subcategoria_refaccion,
                'id_clase_refaccion' => $e->preRegistroRefaccion?->id_clase_refaccion,
                's_clase_refaccion' => $e->preRegistroRefaccion?->claseRefaccion?->s_clase_refaccion,
                'id_usuario_crea' => $e->preRegistroRefaccion?->id_usuario_crea,
                's_nombre_completo' => $e->preRegistroRefaccion?->usuarioCrea?->s_nombre_completo,
            ]);

        [$factura, $base64] = $this->factura($idEmbarque);

        return [
            'embarque' => $this->resumen($embarque),
            'entradas' => $entradas,
            'pendientes' => $pendientes,
            'factura' => $factura,
            'base64' => $base64,
        ];
    }

    public function crear(array $datos, int $idUsuario): Embarque
    {
        return DB::transaction(function () use ($datos, $idUsuario) {
            $embarque = Embarque::create([
                'id_proveedor' => $datos['id_proveedor'],
                'd_fecha_creacion' => now(),
                'id_usuario_crea' => $idUsuario,
                'id_estatus_embarque' => EstatusEmbarque::PENDIENTE,
                'b_activo' => 1,
            ]);

            foreach ($datos['evidencias'] ?? [] as $evidencia) {
                $this->guardarEvidenciaComprimida(
                    $embarque->id_embarque,
                    $evidencia['imagen'],
                    TipoEvidencia::IMAGEN_GENERAL,
                    self::DIR_EVIDENCIAS,
                );
            }

            if (! empty($datos['factura'])) {
                $this->guardarFactura($embarque->id_embarque, $datos['factura'], strtolower($datos['extension'] ?? 'jpg'));
            }

            foreach ($datos['entradas'] as $entrada) {
                $this->registrarEntrada($embarque->id_embarque, $entrada, $idUsuario);
            }

            return $embarque;
        });
    }

    /** Aprueba el embarque: alta de pendientes, stock y precio de existentes. */
    public function aprobar(int $idEmbarque, array $datos, int $idUsuario): void
    {
        DB::transaction(function () use ($idEmbarque, $datos, $idUsuario) {
            $embarque = Embarque::findOrFail($idEmbarque);

            foreach ($datos['pendientes'] ?? [] as $pendiente) {
                Refaccion::create([
                    's_nombre_refaccion' => $pendiente['s_nombre_refaccion'],
                    's_numero_parte' => $pendiente['s_numero_parte'],
                    'id_marca_refaccion' => $pendiente['id_marca_refaccion'],
                    'id_categoria_refaccion' => $pendiente['id_categoria_refaccion'],
                    'id_subcategoria_refaccion' => $pendiente['id_subcategoria_refaccion'],
                    'id_clase_refaccion' => $pendiente['id_clase_refaccion'],
                    'n_precio_compra' => $pendiente['n_precio_compra'],
                    'id_usuario_crea' => $idUsuario,
                    'b_activo' => 1,
                ]);
            }

            foreach ($datos['entradas'] ?? [] as $entrada) {
                $refaccion = Refaccion::findOrFail($entrada['id_refaccion']);
                $refaccion->update([
                    'n_precio_compra' => $entrada['n_precio_compra'],
                    'n_stock_actual' => $refaccion->n_stock_actual + $entrada['n_cantidad'],
                    'id_usuario_edita' => $idUsuario,
                ]);
            }

            $embarque->update(['id_estatus_embarque' => EstatusEmbarque::APROBADO]);
            EntradaEmbarque::where('id_embarque', $idEmbarque)->update(['id_estatus_entrada' => EstatusEntrada::APROBADA]);
        });
    }

    public function rechazar(int $idEmbarque): void
    {
        DB::transaction(function () use ($idEmbarque) {
            Embarque::findOrFail($idEmbarque)->update(['id_estatus_embarque' => EstatusEmbarque::RECHAZADO]);
            EntradaEmbarque::where('id_embarque', $idEmbarque)->update(['id_estatus_entrada' => EstatusEntrada::RECHAZADA]);
        });
    }

    /** Entradas aprobadas agrupadas por refacción (pestaña "Refacciones"). */
    public function refaccionesInsertadas(): Collection
    {
        $existentes = EntradaEmbarque::activo()
            ->where('id_estatus_entrada', EstatusEntrada::APROBADA)
            ->whereNotNull('id_refaccion')
            ->with('refaccion.marcaRefaccion', 'refaccion.categoriaRefaccion', 'refaccion.subcategoriaRefaccion', 'refaccion.claseRefaccion')
            ->get()
            ->groupBy('id_refaccion')
            ->map(fn (Collection $grupo) => [
                'id_entrada_embarque' => $grupo->first()->id_entrada_embarque,
                'id_embarque' => $grupo->first()->id_embarque,
                'id_refaccion' => $grupo->first()->id_refaccion,
                'id_pre_registro_refaccion' => null,
                's_nombre_refaccion' => $grupo->first()->refaccion?->s_nombre_refaccion,
                's_marca_refaccion' => $grupo->first()->refaccion?->marcaRefaccion?->s_marca_refaccion,
                's_categoria_refaccion' => $grupo->first()->refaccion?->categoriaRefaccion?->s_categoria_refaccion,
                's_subcategoria_refaccion' => $grupo->first()->refaccion?->subcategoriaRefaccion?->s_subcategoria_refaccion,
                's_clase_refaccion' => $grupo->first()->refaccion?->claseRefaccion?->s_clase_refaccion,
                's_numero_parte' => $grupo->first()->refaccion?->s_numero_parte,
                'n_cantidad' => $grupo->sum('n_cantidad'),
                'n_precio_compra' => $grupo->first()->n_precio_compra,
                's_codigo_barras' => $grupo->first()->s_codigo_barras,
                'd_fecha_creacion' => $grupo->first()->d_fecha_creacion?->format('Y-m-d\TH:i:s'),
            ])
            ->values();

        $nuevas = EntradaEmbarque::activo()
            ->where('id_estatus_entrada', EstatusEntrada::APROBADA)
            ->whereNotNull('id_pre_registro_refaccion')
            ->with('preRegistroRefaccion.marcaRefaccion', 'preRegistroRefaccion.categoriaRefaccion', 'preRegistroRefaccion.subcategoriaRefaccion', 'preRegistroRefaccion.claseRefaccion')
            ->get()
            ->groupBy('id_pre_registro_refaccion')
            ->map(fn (Collection $grupo) => [
                'id_entrada_embarque' => $grupo->first()->id_entrada_embarque,
                'id_embarque' => $grupo->first()->id_embarque,
                'id_refaccion' => null,
                'id_pre_registro_refaccion' => $grupo->first()->id_pre_registro_refaccion,
                's_nombre_refaccion' => $grupo->first()->preRegistroRefaccion?->s_nombre_refaccion,
                's_marca_refaccion' => $grupo->first()->preRegistroRefaccion?->marcaRefaccion?->s_marca_refaccion,
                's_categoria_refaccion' => $grupo->first()->preRegistroRefaccion?->categoriaRefaccion?->s_categoria_refaccion,
                's_subcategoria_refaccion' => $grupo->first()->preRegistroRefaccion?->subcategoriaRefaccion?->s_subcategoria_refaccion,
                's_clase_refaccion' => $grupo->first()->preRegistroRefaccion?->claseRefaccion?->s_clase_refaccion,
                's_numero_parte' => $grupo->first()->preRegistroRefaccion?->s_numero_parte,
                'n_cantidad' => $grupo->sum('n_cantidad'),
                'n_precio_compra' => $grupo->first()->n_precio_compra,
                's_codigo_barras' => $grupo->first()->s_codigo_barras,
                'd_fecha_creacion' => $grupo->first()->d_fecha_creacion?->format('Y-m-d\TH:i:s'),
            ])
            ->values();

        return $existentes->concat($nuevas);
    }

    /** Embarques aprobados donde entró una refacción (o pre-registro) dada. */
    public function embarquesDeRefaccion(?int $idRefaccion = null, ?int $idPreRegistro = null): Collection
    {
        $columna = $idRefaccion !== null ? 'id_refaccion' : 'id_pre_registro_refaccion';
        $valor = $idRefaccion ?? $idPreRegistro;

        $embarques = Embarque::activo()
            ->with(['proveedor', 'usuarioCrea', 'estatusEmbarque'])
            ->where('id_estatus_embarque', EstatusEmbarque::APROBADO)
            ->whereHas('entradasEmbarque', fn ($q) => $q
                ->where($columna, $valor)
                ->where('b_activo', 1)
                ->where('id_estatus_entrada', EstatusEntrada::APROBADA))
            ->orderByDesc('id_embarque')
            ->get();

        $entradas = EntradaEmbarque::activo()
            ->where('id_estatus_entrada', EstatusEntrada::APROBADA)
            ->where($columna, $valor)
            ->with('refaccion.marcaRefaccion', 'preRegistroRefaccion.marcaRefaccion')
            ->get()
            ->groupBy('id_embarque');

        return $embarques->map(fn (Embarque $e) => [
            ...$this->resumen($e),
            'refacciones' => $entradas->get($e->id_embarque, collect())->map(fn (EntradaEmbarque $entrada) => [
                'id_embarque' => $entrada->id_embarque,
                'id_refaccion' => $entrada->id_refaccion,
                's_nombre_refaccion' => $entrada->refaccion?->s_nombre_refaccion ?? $entrada->preRegistroRefaccion?->s_nombre_refaccion,
                's_marca_refaccion' => $entrada->refaccion?->marcaRefaccion?->s_marca_refaccion ?? $entrada->preRegistroRefaccion?->marcaRefaccion?->s_marca_refaccion,
                's_numero_parte' => $entrada->refaccion?->s_numero_parte ?? $entrada->preRegistroRefaccion?->s_numero_parte,
                'n_cantidad' => $entrada->n_cantidad,
                's_codigo_barras' => $entrada->s_codigo_barras,
            ])->values(),
        ]);
    }

    private function resumen(Embarque $e): array
    {
        return [
            'id_embarque' => $e->id_embarque,
            'id_proveedor' => $e->id_proveedor,
            's_proveedor' => $e->proveedor?->s_proveedor,
            'd_fecha_creacion' => $e->d_fecha_creacion?->format('Y-m-d H:i:s'),
            'id_usuario_crea' => $e->id_usuario_crea,
            's_nombre_completo' => $e->usuarioCrea?->s_nombre_completo,
            'id_estatus_embarque' => $e->id_estatus_embarque,
            's_estatus_embarque' => $e->estatusEmbarque?->s_estatus_embarque,
        ];
    }

    private function guardarEvidenciaComprimida(int $idEmbarque, string $imagenBase64, int $tipoEvidencia, string $directorio): void
    {
        $evidencia = EvidenciaEmbarque::create([
            'id_embarque' => $idEmbarque,
            'id_tipo_evidencia' => $tipoEvidencia,
            's_evidencia_embarque' => 'temp_name',
            'd_fecha_creacion' => now(),
            'b_activo' => 1,
        ]);

        $nombre = sprintf('PE_EM%d_TE%d_%d.jpg', $idEmbarque, $tipoEvidencia, $evidencia->id_evidencia_embarque);

        if (! $this->imageService->guardarBase64Comprimida($imagenBase64, $directorio, $nombre)) {
            throw new DomainException('Hubo un error al comprimir la imagen', 422);
        }

        $evidencia->update(['s_evidencia_embarque' => $nombre]);
    }

    private function guardarFactura(int $idEmbarque, string $base64, string $extension): void
    {
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $this->guardarEvidenciaComprimida($idEmbarque, $base64, TipoEvidencia::IMAGEN_FACTURA, self::DIR_FACTURA_IMG);

            return;
        }

        if ($extension === 'pdf') {
            $evidencia = EvidenciaEmbarque::create([
                'id_embarque' => $idEmbarque,
                'id_tipo_evidencia' => TipoEvidencia::PDF_FACTURA,
                's_evidencia_embarque' => 'temp_name',
                'd_fecha_creacion' => now(),
                'b_activo' => 1,
            ]);

            $nombre = sprintf('PE_EM%d_TE%d_%d.pdf', $idEmbarque, TipoEvidencia::PDF_FACTURA, $evidencia->id_evidencia_embarque);
            $this->imageService->guardarPdfBase64($base64, self::DIR_FACTURA_PDF, $nombre);
            $evidencia->update(['s_evidencia_embarque' => $nombre]);
        }
    }

    private function registrarEntrada(int $idEmbarque, array $entrada, int $idUsuario): void
    {
        $idPreRegistro = null;

        if (empty($entrada['id_refaccion'])) {
            $idPreRegistro = PreRegistroRefaccion::create([
                's_nombre_refaccion' => $entrada['s_nombre_refaccion'],
                's_numero_parte' => $entrada['s_numero_parte'],
                'id_marca_refaccion' => $entrada['id_marca_refaccion'],
                'id_categoria_refaccion' => $entrada['id_categoria_refaccion'],
                'id_subcategoria_refaccion' => $entrada['id_subcategoria_refaccion'],
                'id_clase_refaccion' => $entrada['id_clase_refaccion'],
                'n_precio_compra' => $entrada['n_precio_compra'],
                'id_usuario_crea' => $idUsuario,
                'b_activo' => 1,
            ])->id_pre_registro_refaccion;
        }

        EntradaEmbarque::create([
            'id_embarque' => $idEmbarque,
            'id_refaccion' => $entrada['id_refaccion'] ?? null,
            'id_pre_registro_refaccion' => $idPreRegistro,
            'id_estatus_entrada' => EstatusEntrada::PENDIENTE,
            'n_cantidad' => $entrada['n_cantidad_recibida'],
            'n_precio_compra' => $entrada['n_precio_compra'],
            's_codigo_barras' => $entrada['codigo_barras'] ?? null,
            'd_fecha_creacion' => now(),
            'b_activo' => 1,
        ]);
    }

    /** @return array{0: ?array, 1: string} factura (PDF con preferencia) y su base64 si es PDF */
    private function factura(int $idEmbarque): array
    {
        $pdf = EvidenciaEmbarque::activo()
            ->where('id_embarque', $idEmbarque)
            ->where('id_tipo_evidencia', TipoEvidencia::PDF_FACTURA)
            ->first();

        if ($pdf) {
            $ruta = public_path(self::DIR_FACTURA_PDF . '/' . $pdf->s_evidencia_embarque);
            $base64 = File::exists($ruta) ? base64_encode(File::get($ruta)) : '';

            return [
                ['s_evidencia_embarque' => $pdf->s_evidencia_embarque, 'id_tipo_evidencia' => $pdf->id_tipo_evidencia],
                $base64,
            ];
        }

        $imagen = EvidenciaEmbarque::activo()
            ->where('id_embarque', $idEmbarque)
            ->where('id_tipo_evidencia', TipoEvidencia::IMAGEN_FACTURA)
            ->first();

        return [
            $imagen ? ['s_evidencia_embarque' => $imagen->s_evidencia_embarque, 'id_tipo_evidencia' => $imagen->id_tipo_evidencia] : null,
            '',
        ];
    }
}

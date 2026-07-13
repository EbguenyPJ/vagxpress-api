<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\CategoriaGasto;
use App\Models\Gasto;
use App\Models\TipoEvidencia;
use App\Models\TipoGasto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GastoService
{
    private const DIRECTORIO_EVIDENCIAS = 'evidencias_gastos';

    /** Extensión → nombre del tipo de evidencia en catálogo. */
    private const TIPO_POR_EXTENSION = [
        'jpg' => 'imagen', 'jpeg' => 'imagen', 'png' => 'imagen', 'gif' => 'imagen',
        'mp4' => 'video', 'avi' => 'video', 'mov' => 'video',
        'mp3' => 'audio', 'wav' => 'audio',
        'pdf' => 'documento', 'doc' => 'documento', 'docx' => 'documento',
        'xls' => 'documento', 'xlsx' => 'documento',
    ];

    public function listar(): Collection
    {
        return Gasto::activo()
            ->with(['tipoGasto.categoriaGasto', 'sucursal', 'usuarioCrea'])
            ->orderByDesc('d_fecha_gasto')
            ->get()
            ->map(fn (Gasto $g) => [
                'id_gasto' => $g->id_gasto,
                'id_tipo_gasto' => $g->id_tipo_gasto,
                'id_tipo_evidencia' => $g->id_tipo_evidencia,
                'id_sucursal' => $g->id_sucursal,
                'n_cantidad' => $g->n_cantidad,
                'n_costo' => $g->n_costo,
                's_concepto' => $g->s_concepto,
                's_evidencia' => $g->s_evidencia,
                'd_fecha_gasto' => $g->d_fecha_gasto?->format('Y-m-d H:i:s'),
                'd_fecha_creacion' => $g->d_fecha_creacion?->format('Y-m-d H:i:s'),
                'id_usuario_crea' => $g->id_usuario_crea,
                'b_activo' => $g->b_activo,
                'b_movil' => $g->b_movil,
                's_sucursal' => $g->sucursal?->s_sucursal,
                's_tipo_gasto' => $g->tipoGasto?->s_tipo_gasto,
                's_categoria_gasto' => $g->tipoGasto?->categoriaGasto?->s_categoria_gasto,
                'usuario_crea' => $g->usuarioCrea?->s_nombre_completo,
                'url_evidencia' => $g->s_evidencia ? asset(self::DIRECTORIO_EVIDENCIAS . '/' . $g->s_evidencia) : null,
            ]);
    }

    public function tiposDeGasto(): Collection
    {
        return TipoGasto::activo()->with('categoriaGasto')->get()->map(fn (TipoGasto $t) => [
            'id_tipo_gasto' => $t->id_tipo_gasto,
            'id_categoria_gasto' => $t->id_categoria_gasto,
            's_tipo_gasto' => $t->s_tipo_gasto,
            's_categoria_gasto' => $t->categoriaGasto?->s_categoria_gasto,
        ]);
    }

    public function categoriasDeGasto()
    {
        return CategoriaGasto::activo()->get();
    }

    public function crearTipoGasto(array $datos): TipoGasto
    {
        return TipoGasto::create([
            'id_categoria_gasto' => $datos['id_categoria_gasto'],
            's_tipo_gasto' => $datos['s_tipo_gasto'],
            'b_activo' => 1,
        ]);
    }

    /**
     * Crea un gasto con evidencia base64 opcional.
     * El archivo se guarda como eg{m}_{id}.{ext} en public/evidencias_gastos.
     */
    public function crear(array $datos, ?int $idUsuario, bool $movil): Gasto
    {
        return DB::transaction(function () use ($datos, $idUsuario, $movil) {
            $gasto = Gasto::create([
                'id_tipo_gasto' => $datos['id_tipo_gasto'],
                'id_sucursal' => $datos['id_sucursal'] ?? 1,
                'n_cantidad' => $datos['n_cantidad'] ?? 1,
                'n_costo' => $datos['n_costo'] ?? 0,
                's_concepto' => $datos['s_concepto'],
                'd_fecha_gasto' => $datos['d_fecha_gasto'] ?? now(),
                'd_fecha_creacion' => now(),
                'id_usuario_crea' => $idUsuario,
                'b_movil' => $movil,
                'b_activo' => 1,
            ]);

            if (! empty($datos['archivo']['evidencia'])) {
                $this->adjuntarEvidencia($gasto, $datos['archivo']['evidencia'], strtolower($datos['extension'] ?? ''), $movil);
            }

            return $gasto;
        });
    }

    private function adjuntarEvidencia(Gasto $gasto, string $base64, string $extension, bool $movil): void
    {
        $tipo = self::TIPO_POR_EXTENSION[$extension]
            ?? throw new DomainException('El tipo de evidencia no está permitido', 422);

        $idTipoEvidencia = TipoEvidencia::where('s_tipo_evidencia', $tipo)->value('id_tipo_evidencia');

        $limpio = preg_replace('/^data:[a-zA-Z0-9\/\-\.+]+;base64,/', '', $base64);
        $limpio = str_replace(' ', '+', $limpio);

        File::ensureDirectoryExists(public_path(self::DIRECTORIO_EVIDENCIAS));
        $nombre = ($movil ? 'egm_' : 'eg_') . $gasto->id_gasto . '.' . $extension;
        File::put(public_path(self::DIRECTORIO_EVIDENCIAS) . '/' . $nombre, base64_decode($limpio));

        $gasto->update(['s_evidencia' => $nombre, 'id_tipo_evidencia' => $idTipoEvidencia]);
    }
}

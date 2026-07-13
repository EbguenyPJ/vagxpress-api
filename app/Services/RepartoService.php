<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Empleado;
use App\Models\EstatusOrden;
use App\Models\EvidenciaOrden;
use App\Models\Orden;
use App\Models\PuntoRuta;
use App\Models\TipoRuta;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Ciclo de reparto: asignación de órdenes a repartidores, ejecución del
 * reparto con evidencias fotográficas, firma, y trazas GPS de ida/regreso.
 */
class RepartoService
{
    private const TIPO_EVIDENCIA_SALIDA = 4;
    private const TIPO_EVIDENCIA_FIN = 5;
    private const RUTA_SALIDA = 1;
    private const RUTA_REGRESO = 2;

    public function __construct(private readonly ImageService $imageService)
    {
    }

    public function ordenesPendientes(): Collection
    {
        return Orden::activo()
            ->with(['destino', 'estatusOrden'])
            ->where('id_estatus_orden', EstatusOrden::PENDIENTE_ASIGNACION)
            ->get()
            ->map(fn (Orden $o) => [
                'id_orden' => $o->id_orden,
                's_nombre_destino' => $o->destino?->s_nombre_destino,
                's_direccion' => $o->destino?->s_direccion,
                's_estatus_orden' => $o->estatusOrden?->s_estatus_orden,
                'd_fecha_entrega' => $o->d_fecha_entrega,
            ]);
    }

    public function ordenesAsignadas(int $idRepartidor): Collection
    {
        return Orden::activo()
            ->with(['destino', 'estatusOrden'])
            ->where('id_estatus_orden', EstatusOrden::ASIGNADA)
            ->where('id_repartidor', $idRepartidor)
            ->get()
            ->map(fn (Orden $o) => [
                'id_orden' => $o->id_orden,
                's_nombre_destino' => $o->destino?->s_nombre_destino,
                's_direccion' => $o->destino?->s_direccion,
                'id_repartidor' => $o->id_repartidor,
                's_estatus_orden' => $o->estatusOrden?->s_estatus_orden,
            ]);
    }

    public function repartidores(): Collection
    {
        return User::activo()
            ->whereHas('empleado', fn ($q) => $q->where('id_tipo_empleado', Empleado::TIPO_REPARTIDOR))
            ->get(['id', 's_nombre_completo'])
            ->map(fn (User $u) => ['id' => $u->id, 's_nombre_completo' => $u->s_nombre_completo]);
    }

    public function asignar(int $idOrden, int $idRepartidor): Orden
    {
        $orden = Orden::findOrFail($idOrden);
        $orden->update([
            'id_estatus_orden' => EstatusOrden::ASIGNADA,
            'id_repartidor' => $idRepartidor,
            'd_fecha_asignacion' => now(),
        ]);

        return $orden;
    }

    public function detalleOrden(int $idOrden): array
    {
        $orden = Orden::activo()->with(['destino', 'estatusOrden', 'ordenesProductos' => fn ($q) => $q->where('b_activo', 1)])->findOrFail($idOrden);

        return [
            ...$this->resumenOrden($orden),
            'productos' => $orden->ordenesProductos,
        ];
    }

    public function listarRepartos(): Collection
    {
        return Orden::activo()
            ->with(['destino', 'estatusOrden'])
            ->get()
            ->map(fn (Orden $o) => $this->resumenOrden($o));
    }

    public function detalleReparto(int $idOrden): array
    {
        $orden = Orden::activo()->with(['destino', 'estatusOrden', 'ordenesProductos' => fn ($q) => $q->where('b_activo', 1)])->findOrFail($idOrden);

        $evidencias = fn (int $tipo) => EvidenciaOrden::activo()
            ->where('id_orden', $idOrden)
            ->where('id_tipo_evidencia', $tipo)
            ->get(['s_evidencia_orden']);

        $ruta = fn (int $tipo) => PuntoRuta::activo()
            ->where('id_orden', $idOrden)
            ->where('id_tipo_ruta', $tipo)
            ->get(['n_latitud', 'n_longitud', 'timestamp']);

        return [
            'orden' => [...$this->resumenOrden($orden), 'productos' => $orden->ordenesProductos],
            'evidencias_salida_reparto' => $evidencias(self::TIPO_EVIDENCIA_SALIDA),
            'evidencias_fin_reparto' => $evidencias(self::TIPO_EVIDENCIA_FIN),
            'ruta_salida' => $ruta(self::RUTA_SALIDA),
            'ruta_regreso' => $ruta(self::RUTA_REGRESO),
        ];
    }

    /**
     * Registra la ejecución completa de un reparto: evidencias de salida y
     * fin, firma del cliente, trazas GPS y horas; la orden queda Entregada.
     */
    public function registrarReparto(array $datos): void
    {
        DB::transaction(function () use ($datos) {
            $orden = Orden::findOrFail($datos['id_orden']);

            foreach ($datos['evidencias_inicio_reparto'] ?? [] as $evidencia) {
                $this->guardarEvidenciaOrden($orden->id_orden, $evidencia['imagen'], self::TIPO_EVIDENCIA_SALIDA, 'evidenciasVXM/imgEvidenciasSalidaReparto', 'PSR');
            }

            $orden->update([
                'd_inicio_reparto' => $datos['hora_inicio_reparto'] ?? null,
                'd_fin_reparto' => $datos['hora_fin_reparto'] ?? null,
                'd_inicio_regreso' => $datos['hora_inicio_regreso'] ?? null,
                'd_fin_regreso' => $datos['hora_fin_regreso'] ?? null,
                's_nombre_recibe' => $datos['s_nombre_recibe'] ?? null,
                'id_estatus_orden' => EstatusOrden::ENTREGADA,
            ]);

            $this->guardarPuntosRuta($orden->id_orden, $datos['ubicaciones_reparto'] ?? [], self::RUTA_SALIDA);

            foreach ($datos['evidencias_fin_reparto'] ?? [] as $evidencia) {
                $this->guardarEvidenciaOrden($orden->id_orden, $evidencia['imagen'], self::TIPO_EVIDENCIA_FIN, 'evidenciasVXM/imgEvidenciasFinReparto', 'PFR');
            }

            if (! empty($datos['firma_cliente'])) {
                $nombre = 'FC_OS' . $orden->id_orden . '.jpg';
                if (! $this->imageService->guardarBase64Comprimida($datos['firma_cliente'], 'evidenciasVXM/imgFirmas', $nombre)) {
                    throw new DomainException('Hubo un error al comprimir la imagen', 422);
                }
                $orden->update(['s_firma' => $nombre]);
            }

            $this->guardarPuntosRuta($orden->id_orden, $datos['ubicaciones_regreso'] ?? [], self::RUTA_REGRESO);
        });
    }

    private function resumenOrden(Orden $o): array
    {
        return [
            'id_orden' => $o->id_orden,
            'id_destino' => $o->id_destino,
            's_nombre_destino' => $o->destino?->s_nombre_destino,
            's_direccion' => $o->destino?->s_direccion,
            's_referencia_destino' => $o->destino?->s_referencia_destino,
            's_nota_refaccionista' => $o->s_nota_refaccionista,
            'id_repartidor' => $o->id_repartidor,
            'd_fecha_asignacion' => $o->d_fecha_asignacion,
            'id_estatus_orden' => $o->id_estatus_orden,
            's_estatus_orden' => $o->estatusOrden?->s_estatus_orden,
            'd_fecha_entrega' => $o->d_fecha_entrega,
            's_nombre_recibe' => $o->s_nombre_recibe,
            's_firma' => $o->s_firma,
            'd_inicio_reparto' => $o->d_inicio_reparto,
            'd_fin_reparto' => $o->d_fin_reparto,
            'd_inicio_regreso' => $o->d_inicio_regreso,
            'd_fin_regreso' => $o->d_fin_regreso,
        ];
    }

    private function guardarEvidenciaOrden(int $idOrden, string $base64, int $tipoEvidencia, string $directorio, string $prefijo): void
    {
        $evidencia = EvidenciaOrden::create([
            'id_orden' => $idOrden,
            'id_tipo_evidencia' => $tipoEvidencia,
            's_evidencia_orden' => 'temp_name',
            'b_activo' => 1,
        ]);

        $nombre = sprintf('%s_OS%d_TE%d_%d.jpg', $prefijo, $idOrden, $tipoEvidencia, $evidencia->id_evidencia_orden);

        if (! $this->imageService->guardarBase64Comprimida($base64, $directorio, $nombre)) {
            throw new DomainException('Hubo un error al comprimir la imagen', 422);
        }

        $evidencia->update(['s_evidencia_orden' => $nombre]);
    }

    private function guardarPuntosRuta(int $idOrden, array $ubicaciones, int $tipoRuta): void
    {
        foreach ($ubicaciones as $punto) {
            PuntoRuta::create([
                'id_orden' => $idOrden,
                'id_tipo_ruta' => $tipoRuta,
                'n_latitud' => $punto['latitud'],
                'n_longitud' => $punto['longitud'],
                'timestamp' => $punto['timestamp'],
                'b_activo' => 1,
            ]);
        }
    }
}

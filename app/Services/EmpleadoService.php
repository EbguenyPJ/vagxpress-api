<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Empleado;
use App\Models\Habilidad;
use App\Models\HabilidadEmpleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EmpleadoService
{
    private const DIRECTORIO_IMAGENES = 'empleados';
    private const IMAGEN_POR_DEFECTO = 'empleado-default.png';

    /** Relaciones que alimentan las columnas de la pantalla de empleados. */
    private const RELACIONES_LISTADO = [
        'tipoEmpleado', 'profesion', 'gradoEstudios', 'sucursal', 'estadoDisponibilidad',
    ];

    public function __construct(private readonly ImageService $imageService)
    {
    }

    public function listar(): Collection
    {
        return Empleado::with(self::RELACIONES_LISTADO)->orderBy('id_empleado')->get();
    }

    public function sinUsuario(): Collection
    {
        return Empleado::with(self::RELACIONES_LISTADO)
            ->where('b_es_usuario', 0)
            ->orderByDesc('id_empleado')
            ->get();
    }

    public function porSucursal(int $idSucursal): Collection
    {
        return Empleado::with(self::RELACIONES_LISTADO)
            ->where('id_sucursal', $idSucursal)
            ->orderBy('id_empleado')
            ->get();
    }

    /** El empleado vinculado a un usuario (vía users.id_empleado). */
    public function porUsuario(int $idUsuario): Empleado
    {
        $user = User::findOrFail($idUsuario);

        return Empleado::with('tipoEmpleado')->findOrFail($user->id_empleado);
    }

    public function gerenteDeSucursal(int $idSucursal): Empleado
    {
        return Empleado::with('tipoEmpleado')
            ->where('id_sucursal', $idSucursal)
            ->whereHas('tipoEmpleado', fn ($q) => $q->where('s_tipo_empleado', 'like', '%gerente%'))
            ->firstOrFail();
    }

    public function crear(array $datos): Empleado
    {
        $duplicado = Empleado::where('s_nombre', $datos['s_nombre'])
            ->where('s_apellido_paterno', $datos['s_apellido_paterno'])
            ->where('s_apellido_materno', $datos['s_apellido_materno'] ?? null)
            ->whereDate('d_fecha_nacimiento', $datos['d_fecha_nacimiento'])
            ->exists();

        if ($duplicado) {
            throw new DomainException('Ya existe un empleado con estos datos');
        }

        $telefono = $this->limpiarTelefono($datos['s_telefono']);
        if (empty($telefono)) {
            throw new DomainException('El número de teléfono no es válido', 422);
        }

        return DB::transaction(function () use ($datos, $telefono) {
            $empleado = Empleado::create([
                ...array_intersect_key($datos, array_flip([
                    'id_tipo_empleado', 'id_profesion', 'id_grado_estudios', 'id_sucursal', 'id_sexo',
                    's_nombre', 's_apellido_paterno', 's_apellido_materno', 's_correo', 's_direccion',
                    's_contacto_emergencia', 'd_fecha_nacimiento', 'd_fecha_ingreso',
                ])),
                's_telefono' => $telefono,
                's_telefono_contacto_emergencia' => $this->limpiarTelefono($datos['s_telefono_contacto_emergencia'] ?? null),
                'id_estado_disponibilidad' => 1, // Disponible
                'b_es_usuario' => 0,
                'b_activo' => 1,
                's_foto_empleado' => self::IMAGEN_POR_DEFECTO,
            ]);

            $empleado->s_qr_empleado = 'ESC-' . $empleado->id_empleado;
            $empleado->s_foto_empleado = $this->imageService->guardarBase64(
                $datos['s_foto_empleado'] ?? null,
                self::DIRECTORIO_IMAGENES,
                "empleado_{$empleado->id_empleado}_",
                self::IMAGEN_POR_DEFECTO,
            );
            $empleado->save();

            $this->asignarHabilidadesPorTipo($empleado->id_empleado, $empleado->id_tipo_empleado);

            return $empleado;
        });
    }

    public function actualizar(int $idEmpleado, array $datos): Empleado
    {
        $empleado = Empleado::findOrFail($idEmpleado);
        $tipoAnterior = $empleado->id_tipo_empleado;

        return DB::transaction(function () use ($empleado, $datos, $tipoAnterior, $idEmpleado) {
            $empleado->fill(array_intersect_key($datos, array_flip([
                'id_tipo_empleado', 'id_profesion', 'id_grado_estudios', 'id_sucursal', 'id_sexo',
                's_nombre', 's_apellido_paterno', 's_apellido_materno', 's_correo', 's_direccion',
                's_contacto_emergencia', 'd_fecha_nacimiento', 'd_fecha_ingreso',
            ])));

            foreach (['s_telefono', 's_telefono_contacto_emergencia'] as $campo) {
                if (array_key_exists($campo, $datos)) {
                    $empleado->{$campo} = $this->limpiarTelefono($datos[$campo]);
                }
            }

            if (! empty($datos['s_foto_empleado'])) {
                $this->imageService->eliminar(self::DIRECTORIO_IMAGENES, $empleado->s_foto_empleado, self::IMAGEN_POR_DEFECTO);
                $empleado->s_foto_empleado = $this->imageService->guardarBase64(
                    $datos['s_foto_empleado'],
                    self::DIRECTORIO_IMAGENES,
                    "empleado_{$idEmpleado}_",
                    self::IMAGEN_POR_DEFECTO,
                );
            }

            $empleado->save();

            if (isset($datos['id_tipo_empleado']) && (int) $datos['id_tipo_empleado'] !== (int) $tipoAnterior) {
                $this->reemplazarHabilidadesPorTipo($idEmpleado, (int) $datos['id_tipo_empleado']);
            }

            return $empleado;
        });
    }

    /** Habilidades activas del empleado con su descripción. */
    public function habilidades(int $idEmpleado)
    {
        Empleado::findOrFail($idEmpleado);

        return HabilidadEmpleado::query()
            ->where('id_empleado', $idEmpleado)
            ->where('b_activo', 1)
            ->with('habilidad.tipoEmpleado')
            ->get()
            ->sortBy(fn (HabilidadEmpleado $h) => $h->habilidad?->s_habilidad_empleado)
            ->values()
            ->map(fn (HabilidadEmpleado $h) => [
                'id_habilidad_empleado' => $h->id_habilidad_empleado,
                'id_habilidad' => $h->id_habilidad,
                'id_empleado' => $h->id_empleado,
                'n_nivel_dominio' => $h->n_nivel_dominio,
                'b_activo' => $h->b_activo,
                's_habilidad_empleado' => $h->habilidad?->s_habilidad_empleado,
                's_tipo_empleado' => $h->habilidad?->tipoEmpleado?->s_tipo_empleado,
            ]);
    }

    /** Actualiza el nivel de dominio de las habilidades recibidas. */
    public function actualizarHabilidades(int $idEmpleado, array $habilidades)
    {
        Empleado::findOrFail($idEmpleado);

        DB::transaction(function () use ($idEmpleado, $habilidades) {
            foreach ($habilidades as $habilidad) {
                HabilidadEmpleado::where('id_habilidad_empleado', $habilidad['id_habilidad_empleado'])
                    ->where('id_empleado', $idEmpleado)
                    ->where('id_habilidad', $habilidad['id_habilidad'])
                    ->update(['n_nivel_dominio' => $habilidad['n_nivel_dominio']]);
            }
        });

        return $this->habilidades($idEmpleado);
    }

    /** Da de alta las habilidades base del tipo de empleado con nivel 1. */
    private function asignarHabilidadesPorTipo(int $idEmpleado, int $idTipoEmpleado): void
    {
        $habilidades = Habilidad::activo()->where('id_tipo_empleado', $idTipoEmpleado)->pluck('id_habilidad');

        HabilidadEmpleado::insert(
            $habilidades->map(fn ($idHabilidad) => [
                'id_habilidad' => $idHabilidad,
                'id_empleado' => $idEmpleado,
                'n_nivel_dominio' => 1,
                'b_activo' => 1,
            ])->all()
        );
    }

    private function reemplazarHabilidadesPorTipo(int $idEmpleado, int $idTipoEmpleado): void
    {
        HabilidadEmpleado::where('id_empleado', $idEmpleado)->delete();
        $this->asignarHabilidadesPorTipo($idEmpleado, $idTipoEmpleado);
    }

    private function limpiarTelefono(?string $telefono): ?string
    {
        return $telefono === null ? null : (preg_replace('/[^0-9]/', '', $telefono) ?: null);
    }
}

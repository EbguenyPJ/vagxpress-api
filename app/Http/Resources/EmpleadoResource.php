<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Empleado con los nombres de sus catálogos, tal como los consume
 * la pantalla de empleados.
 */
class EmpleadoResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge($this->resource->attributesToArray(), [
            'd_fecha_nacimiento' => $this->d_fecha_nacimiento?->toDateString(),
            'd_fecha_ingreso' => $this->d_fecha_ingreso?->toDateString(),
            's_tipo_empleado' => $this->whenLoaded('tipoEmpleado', fn () => $this->tipoEmpleado?->s_tipo_empleado),
            's_profesion' => $this->whenLoaded('profesion', fn () => $this->profesion?->s_profesion),
            's_grado_estudios' => $this->whenLoaded('gradoEstudios', fn () => $this->gradoEstudios?->s_grado_estudios),
            's_sucursal' => $this->whenLoaded('sucursal', fn () => $this->sucursal?->s_sucursal),
            's_estado_disponibilidad' => $this->whenLoaded('estadoDisponibilidad', fn () => $this->estadoDisponibilidad?->s_estado_disponibilidad),
        ]);
    }
}

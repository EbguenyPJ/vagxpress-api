<?php

namespace App\Services;

use App\Models\ModuloUsuario;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuloService
{
    /**
     * Módulos activos asignados a un usuario, con su categoría.
     */
    public function modulosDeUsuario(int $idUsuario): Collection
    {
        return ModuloUsuario::query()
            ->where('id_usuario', $idUsuario)
            ->where('b_activo', 1)
            ->with('modulo.categoriaModulo')
            ->get()
            ->map(fn (ModuloUsuario $asignacion) => [
                'id_modulo' => $asignacion->id_modulo,
                'id_usuario' => $asignacion->id_usuario,
                'id_categoria_modulo' => $asignacion->modulo?->id_categoria_modulo,
                's_categoria_modulo' => $asignacion->modulo?->categoriaModulo?->s_categoria_modulo,
                's_modulo' => $asignacion->modulo?->s_modulo,
                's_ruta' => $asignacion->modulo?->s_ruta,
                's_icono' => $asignacion->modulo?->s_icono,
            ]);
    }

    /**
     * Reemplaza los módulos activos del usuario por la lista recibida,
     * conservando el historial (las asignaciones removidas quedan inactivas).
     */
    public function sincronizar(int $idUsuario, array $idsModulos): Collection
    {
        DB::transaction(function () use ($idUsuario, $idsModulos) {
            ModuloUsuario::where('id_usuario', $idUsuario)->update(['b_activo' => 0]);

            foreach ($idsModulos as $idModulo) {
                ModuloUsuario::updateOrCreate(
                    ['id_usuario' => $idUsuario, 'id_modulo' => $idModulo],
                    ['b_activo' => 1],
                );
            }
        });

        return $this->modulosDeUsuario($idUsuario);
    }
}

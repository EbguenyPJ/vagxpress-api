<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\SincronizarModulosRequest;
use App\Models\CategoriaModulo;
use App\Models\Modulo;
use App\Services\ModuloService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ModuloController extends Controller
{
    public function __construct(private readonly ModuloService $moduloService)
    {
    }

    public function index(): JsonResponse
    {
        $modulos = Modulo::activo()
            ->with('categoriaModulo')
            ->whereHas('categoriaModulo', fn ($q) => $q->where('b_activo', 1))
            ->get()
            ->map(fn (Modulo $modulo) => [
                'id_modulo' => $modulo->id_modulo,
                'id_categoria_modulo' => $modulo->id_categoria_modulo,
                's_categoria_modulo' => $modulo->categoriaModulo?->s_categoria_modulo,
                's_modulo' => $modulo->s_modulo,
                's_ruta' => $modulo->s_ruta,
                's_icono' => $modulo->s_icono,
            ]);

        return ApiResponse::ok($modulos, 'Módulos obtenidos correctamente');
    }

    public function categorias(): JsonResponse
    {
        return ApiResponse::ok(
            CategoriaModulo::activo()->get(),
            'Categorías de módulos obtenidas correctamente'
        );
    }

    public function deUsuario(int $idUsuario): JsonResponse
    {
        return ApiResponse::ok(
            $this->moduloService->modulosDeUsuario($idUsuario),
            'Módulos del usuario obtenidos correctamente'
        );
    }

    public function sincronizar(SincronizarModulosRequest $request, int $idUsuario): JsonResponse
    {
        $modulos = $this->moduloService->sincronizar($idUsuario, $request->validated('modulos'));

        return ApiResponse::ok($modulos, 'Módulos actualizados correctamente');
    }
}

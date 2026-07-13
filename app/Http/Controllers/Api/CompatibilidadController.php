<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Compatibilidad\GuardarReglaRequest;
use App\Services\CompatibilidadService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompatibilidadController extends Controller
{
    public function __construct(private readonly CompatibilidadService $compatibilidadService)
    {
    }

    public function catalogosVehiculos(): JsonResponse
    {
        return ApiResponse::ok(
            $this->compatibilidadService->catalogosVehiculos(),
            'Catálogos vehiculares obtenidos correctamente'
        );
    }

    public function storeRegla(GuardarReglaRequest $request): JsonResponse
    {
        $idRegla = $this->compatibilidadService->crearRegla(
            $request->validated('id_refaccion'),
            $request->validated(),
            $request->user()->id,
        );

        return ApiResponse::created(['id_regla' => $idRegla], 'Regla de compatibilidad creada correctamente');
    }

    public function reglasDeRefaccion(int $idRefaccion): JsonResponse
    {
        return ApiResponse::ok(
            $this->compatibilidadService->reglasDeRefaccion($idRefaccion),
            'Reglas de compatibilidad obtenidas correctamente'
        );
    }

    public function destroyRegla(int $idRegla): JsonResponse
    {
        $this->compatibilidadService->eliminarRegla($idRegla);

        return ApiResponse::ok(null, 'Regla de compatibilidad eliminada correctamente');
    }

    public function buscarCompatibles(Request $request): JsonResponse
    {
        return ApiResponse::ok(
            $this->compatibilidadService->buscarCompatibles($request->only([
                'id_marca_vehiculo', 'id_modelo_vehiculo', 'id_generacion', 'id_motor',
            ])),
            'Refacciones compatibles obtenidas correctamente'
        );
    }
}

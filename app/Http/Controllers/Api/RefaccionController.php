<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Refacciones\ActualizarRefaccionRequest;
use App\Http\Requests\Refacciones\CrearRefaccionesMasivoRequest;
use App\Http\Requests\Refacciones\CrearRefaccionRequest;
use App\Http\Resources\RefaccionDetalleResource;
use App\Http\Resources\RefaccionListadoResource;
use App\Services\RefaccionService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class RefaccionController extends Controller
{
    public function __construct(private readonly RefaccionService $refaccionService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            RefaccionListadoResource::collection($this->refaccionService->listar()),
            'Refacciones obtenidas correctamente'
        );
    }

    public function show(int $idRefaccion): JsonResponse
    {
        return ApiResponse::ok(
            new RefaccionDetalleResource($this->refaccionService->porId($idRefaccion)),
            'Refacción obtenida correctamente'
        );
    }

    public function store(CrearRefaccionRequest $request): JsonResponse
    {
        $refaccion = $this->refaccionService->crear($request->validated(), $request->user()->id);

        return ApiResponse::created($refaccion, 'Refacción creada correctamente');
    }

    public function update(ActualizarRefaccionRequest $request, int $idRefaccion): JsonResponse
    {
        $refaccion = $this->refaccionService->actualizar($idRefaccion, $request->validated(), $request->user()->id);

        return ApiResponse::ok($refaccion, 'Refacción actualizada correctamente');
    }

    public function storeMasivo(CrearRefaccionesMasivoRequest $request): JsonResponse
    {
        $creadas = $this->refaccionService->crearMasivo($request->validated('refacciones'), $request->user()->id);

        return ApiResponse::created($creadas, count($creadas) . ' refacciones han sido creadas correctamente');
    }
}

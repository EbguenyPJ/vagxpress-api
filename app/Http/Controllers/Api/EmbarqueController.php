<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Embarques\AprobarEmbarqueRequest;
use App\Http\Requests\Embarques\CrearEmbarqueRequest;
use App\Services\EmbarqueService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmbarqueController extends Controller
{
    public function __construct(private readonly EmbarqueService $embarqueService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok($this->embarqueService->listar(), 'Embarques obtenidos');
    }

    public function show(int $idEmbarque): JsonResponse
    {
        return ApiResponse::ok($this->embarqueService->detalle($idEmbarque), 'Embarque obtenido');
    }

    public function store(CrearEmbarqueRequest $request): JsonResponse
    {
        $this->embarqueService->crear($request->validated(), $request->user()->id);

        return ApiResponse::created(null, 'Embarque creado exitosamente');
    }

    public function aprobar(AprobarEmbarqueRequest $request, int $idEmbarque): JsonResponse
    {
        $this->embarqueService->aprobar($idEmbarque, $request->validated(), $request->user()->id);

        return ApiResponse::ok(null, 'Embarque aprobado exitosamente');
    }

    public function rechazar(int $idEmbarque): JsonResponse
    {
        $this->embarqueService->rechazar($idEmbarque);

        return ApiResponse::ok(null, 'Embarque rechazado exitosamente');
    }

    public function refaccionesInsertadas(): JsonResponse
    {
        return ApiResponse::ok($this->embarqueService->refaccionesInsertadas(), 'Refacciones insertadas obtenidas');
    }

    public function embarquesDeRefaccion(int $idRefaccion): JsonResponse
    {
        return ApiResponse::ok(
            $this->embarqueService->embarquesDeRefaccion(idRefaccion: $idRefaccion),
            'Embarques aprobados'
        );
    }

    public function embarquesDePreRegistro(int $idPreRegistro): JsonResponse
    {
        return ApiResponse::ok(
            $this->embarqueService->embarquesDeRefaccion(idPreRegistro: $idPreRegistro),
            'Embarques aprobados'
        );
    }
}

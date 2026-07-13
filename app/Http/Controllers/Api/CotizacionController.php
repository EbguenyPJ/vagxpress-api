<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cotizaciones\CrearCotizacionRequest;
use App\Services\CotizacionService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class CotizacionController extends Controller
{
    public function __construct(private readonly CotizacionService $cotizacionService)
    {
    }

    public function store(CrearCotizacionRequest $request): JsonResponse
    {
        $resultado = $this->cotizacionService->crear($request->validated(), $request->user()->id);

        return ApiResponse::created($resultado['detalle'], 'Cotización creada correctamente');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gastos\CrearGastoRequest;
use App\Services\GastoService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function __construct(private readonly GastoService $gastoService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok($this->gastoService->listar(), 'Lista de gastos obtenida correctamente');
    }

    public function store(CrearGastoRequest $request): JsonResponse
    {
        $gasto = $this->gastoService->crear($request->validated(), $request->user()->id, movil: false);

        return ApiResponse::created(['id_gasto' => $gasto->id_gasto], 'Gasto creado correctamente');
    }

    public function storeMovil(CrearGastoRequest $request): JsonResponse
    {
        $gasto = $this->gastoService->crear($request->validated(), $request->user()->id, movil: true);

        return ApiResponse::created(['id_gasto' => $gasto->id_gasto], 'Gasto creado correctamente');
    }

    public function tipos(): JsonResponse
    {
        return ApiResponse::ok($this->gastoService->tiposDeGasto(), 'Tipos de gasto obtenidos correctamente');
    }

    public function categorias(): JsonResponse
    {
        return ApiResponse::ok($this->gastoService->categoriasDeGasto(), 'Categorías de gasto obtenidas correctamente');
    }

    public function storeTipo(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'id_categoria_gasto' => ['required', 'integer', 'exists:tc_categorias_gastos,id_categoria_gasto'],
            's_tipo_gasto' => ['required', 'string', 'max:255'],
        ]);

        return ApiResponse::created($this->gastoService->crearTipoGasto($validado), 'Tipo de gasto creado correctamente');
    }
}

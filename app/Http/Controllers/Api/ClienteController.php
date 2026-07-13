<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clientes\GuardarClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Services\ClienteService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(private readonly ClienteService $clienteService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            ClienteResource::collection($this->clienteService->listar()),
            'Clientes obtenidos correctamente'
        );
    }

    public function selector(): JsonResponse
    {
        return ApiResponse::ok($this->clienteService->selector(), 'Clientes obtenidos correctamente');
    }

    public function store(GuardarClienteRequest $request): JsonResponse
    {
        $cliente = $this->clienteService->crear($request->validated(), $request->user()->id);

        return ApiResponse::created(new ClienteResource($cliente), 'Cliente creado correctamente');
    }

    public function update(GuardarClienteRequest $request, int $idCliente): JsonResponse
    {
        $cliente = $this->clienteService->actualizar($idCliente, $request->validated(), $request->user()->id);

        return ApiResponse::ok(new ClienteResource($cliente), 'Cliente actualizado correctamente');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proveedores\GuardarProveedorRequest;
use App\Services\ProveedorService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    public function __construct(private readonly ProveedorService $proveedorService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok($this->proveedorService->listar(), 'Proveedores obtenidos correctamente');
    }

    public function store(GuardarProveedorRequest $request): JsonResponse
    {
        return ApiResponse::created($this->proveedorService->crear($request->validated()), 'Proveedor creado con éxito');
    }

    public function update(GuardarProveedorRequest $request, int $idProveedor): JsonResponse
    {
        return ApiResponse::ok($this->proveedorService->actualizar($idProveedor, $request->validated()), 'Proveedor actualizado con éxito');
    }
}

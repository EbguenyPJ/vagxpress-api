<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\CrearVentaRequest;
use App\Http\Resources\VentaResource;
use App\Services\VentaService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct(private readonly VentaService $ventaService)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            VentaResource::collection($this->ventaService->listar()),
            'Ventas obtenidas correctamente'
        );
    }

    public function show(int $idVenta): JsonResponse
    {
        return ApiResponse::ok(
            new VentaResource($this->ventaService->porId($idVenta)),
            'Venta obtenida correctamente'
        );
    }

    public function store(CrearVentaRequest $request): JsonResponse
    {
        $resultado = $this->ventaService->crear($request->validated(), $request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Venta creada correctamente',
            'data' => $resultado['detalle'],
            'ticket_base64' => $resultado['ticket_base64'],
        ], 201);
    }

    /** Ventas pagadas de un día para armar el corte (?fechaHora=YYYY-MM-DD). */
    public function ventasCorte(Request $request): JsonResponse
    {
        $ventas = $this->ventaService->ventasDelDia($request->query('fechaHora'));

        return response()->json([
            'status' => 'success',
            'message' => 'Ventas obtenidas correctamente',
            'total_dia' => round((float) $ventas->sum('n_total'), 2),
            'data' => $ventas->map(fn ($v) => [
                'id_venta' => $v->id_venta,
                'n_total' => $v->n_total,
                'metodo_pago' => $v->metodoPago?->s_metodo_pago,
                'cliente' => $v->cliente?->s_nombre_cliente,
                'telefono' => $v->cliente?->s_numero_telefono,
                'correo' => $v->cliente?->s_correo,
                'created_at' => $v->created_at,
            ]),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cortes\CrearCorteRequest;
use App\Http\Requests\Cortes\SubirEvidenciasCorteRequest;
use App\Services\CorteService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CorteController extends Controller
{
    public function __construct(private readonly CorteService $corteService)
    {
    }

    public function index(): JsonResponse
    {
        $cortes = $this->corteService->listar()->map(fn ($c) => [
            ...$c->attributesToArray(),
            'd_fecha_corte' => $c->d_fecha_corte?->format('Y-m-d'),
            's_nombre_completo' => $c->usuarioCrea?->s_nombre_completo,
        ]);

        return ApiResponse::ok($cortes, 'Cortes obtenidos correctamente');
    }

    public function show(int $idCorte): JsonResponse
    {
        $detalle = $this->corteService->porId($idCorte);

        return ApiResponse::ok([
            'corte' => [
                ...$detalle['corte']->attributesToArray(),
                'd_fecha_corte' => $detalle['corte']->d_fecha_corte?->format('Y-m-d'),
                's_nombre_completo' => $detalle['corte']->usuarioCrea?->s_nombre_completo,
            ],
            'evidencias' => $detalle['evidencias'],
        ], 'Corte obtenido correctamente');
    }

    public function store(CrearCorteRequest $request): JsonResponse
    {
        $resultado = $this->corteService->crear($request->validated(), $request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Corte creado correctamente',
            'data' => [
                'id_corte' => $resultado['corte']->id_corte,
                'total_usuario' => $resultado['total_usuario'],
                'total_ventas' => $resultado['total_ventas'],
                'diferencia' => $resultado['diferencia'],
                'ventas_corte' => $resultado['ventas_corte'],
            ],
        ], 201);
    }

    public function subirEvidencias(SubirEvidenciasCorteRequest $request): JsonResponse
    {
        $this->corteService->subirEvidencias(
            $request->validated('id_corte'),
            $request->validated('evidencias'),
        );

        return ApiResponse::ok(null, 'Evidencias subidas correctamente');
    }

    public function cerrar(int $idCorte): JsonResponse
    {
        $this->corteService->cerrar($idCorte);

        return ApiResponse::ok(null, 'Corte cerrado correctamente');
    }

    public function desglosado(Request $request): JsonResponse
    {
        return ApiResponse::ok(
            $this->corteService->desglosadoDelDia($request->query('fechaHora')),
            'Corte de caja desglosado obtenido correctamente'
        );
    }
}

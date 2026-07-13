<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class VersionController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::ok(
            Version::activo()->orderByDesc('id_version')->get(),
            'Versiones obtenidas correctamente'
        );
    }

    public function ultima(): JsonResponse
    {
        return ApiResponse::ok(
            Version::activo()->orderByDesc('id_version')->first(),
            'Última versión obtenida correctamente'
        );
    }
}

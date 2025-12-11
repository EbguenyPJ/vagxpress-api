<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcategoriaRefaccionController extends Controller
{
    public function getSubcategoriasRefacciones()
    {
        try {
            $data = DB::table('tc_subcategorias_refacciones AS T1')
                ->select(
                    'T1.id_subcategoria_refaccion',
                    'T1.id_categoria_refaccion',
                    'T1.s_subcategoria_refaccion',
                    'T1.s_img_subcategoria_refaccion',
                )
                ->where('b_activo', 1)
                ->get();

            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No hay categorias disponibles',
                ];
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Categorias de modulos obtenidas correctamente',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al obtener categorias de modulos',
                'error' => $e->getMessage()
            ];
        }
    }
}

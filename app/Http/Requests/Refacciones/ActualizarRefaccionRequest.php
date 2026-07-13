<?php

namespace App\Http\Requests\Refacciones;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarRefaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's_nombre_refaccion' => ['required', 'string', 'max:255'],
            's_numero_parte' => ['nullable', 'string', 'max:255'],
            's_imagen_refaccion' => ['nullable', 'string', 'max:255'],
            'n_precio_compra' => ['nullable', 'numeric', 'min:0'],
            'n_precio_venta' => ['nullable', 'numeric', 'min:0'],
            'n_precio_mayoreo' => ['nullable', 'numeric', 'min:0'],
            'n_stock_actual' => ['nullable', 'numeric', 'min:0'],
            'id_marca_refaccion' => ['nullable', 'integer', 'exists:tc_marcas_refacciones,id_marca_refaccion'],
            'id_unidad_medida' => ['nullable', 'integer', 'exists:tc_unidades_medida,id_unidad_medida'],
            'id_proveedor' => ['nullable', 'integer', 'exists:tw_proveedores,id_proveedor'],
            'id_clase_refaccion' => ['nullable', 'integer', 'exists:tc_clases_refacciones,id_clase_refaccion'],
            'id_categoria_refaccion' => ['required', 'integer', 'exists:tc_categorias_refacciones,id_categoria_refaccion'],
            'id_subcategoria_refaccion' => ['required', 'integer', 'exists:tc_subcategorias_refacciones,id_subcategoria_refaccion'],
            'id_posicion_vehiculo' => ['nullable', 'integer', 'exists:tc_posiciones_vehiculo,id_posicion_vehiculo'],
            'id_ubicacion_almacen' => ['nullable', 'integer', 'exists:tc_ubicaciones_almacen,id_ubicacion_almacen'],
            'b_importado' => ['nullable', 'boolean'],
            'refacciones_equivalentes' => ['nullable', 'array'],
            'refacciones_equivalentes.*.id_refaccion' => ['integer', 'exists:tw_refacciones,id_refaccion'],
        ];
    }
}

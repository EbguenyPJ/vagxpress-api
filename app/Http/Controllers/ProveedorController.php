<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;

class ProveedorController extends Controller
{
    public function getProveedores()
    {
        try {
            $data = DB::table('tw_proveedores')
                ->select(
                    '*'
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


    public function getAll()
    {
        try {
            $data = DB::table('tw_proveedores AS T1')
                ->select(
                    'T1.id_proveedor',
                    'T1.s_proveedor',
                    'T1.s_nombre_contacto',
                    'T1.s_telefono',
                    'T1.s_rfc',
                    'T1.s_img_proveedor',
                    'T1.b_activo',
                    'T1.created_at',
                    'T1.updated_at'
                )
                ->where('T1.b_activo', 1)
                ->orderBy('T1.id_proveedor', 'ASC')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No hay proveedores disponibles'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los proveedores',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function crearProveedor(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validar proveedor duplicado
            $proveedorExistente = Proveedor::where('s_proveedor', $request->s_proveedor)
                ->where('s_rfc', $request->s_rfc)
                ->first();

            if ($proveedorExistente) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya existe un proveedor con estos datos'
                ], 409);
            }

            // Limpiar teléfono
            $telefono = preg_replace('/[^0-9]/', '', $request->s_telefono);

            if (!empty($request->s_telefono) && empty($telefono)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El número de teléfono no es válido'
                ], 400);
            }

            // Crear proveedor
            $proveedor = new Proveedor();
            $proveedor->fill([
                's_proveedor'       => $request->s_proveedor,
                's_nombre_contacto' => $request->s_nombre_contacto,
                's_telefono'        => $telefono,
                's_rfc'             => $request->s_rfc,
                'b_activo'          => 1,
                's_img_proveedor'   => 'proveedor-default.png'
            ]);

            $proveedor->save();

            // Guardar imagen base64
            if (!empty($request->s_img_proveedor)) {
                $newImageName = $this->saveBase64ImageProveedor(
                    $request->s_img_proveedor,
                    'proveedor_' . $proveedor->id_proveedor . '_'
                );

                if ($newImageName !== 'proveedor-default.png') {
                    $proveedor->s_img_proveedor = $newImageName;
                }
            }

            $proveedor->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Proveedor creado con éxito',
                'data' => $proveedor
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear proveedor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarProveedor(Request $request, $id_proveedor)
    {
        try {
            $proveedor = Proveedor::find($id_proveedor);

            if (!$proveedor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Proveedor no encontrado'
                ], 404);
            }

            $proveedor->fill($request->only([
                's_proveedor',
                's_nombre_contacto',
                's_rfc',
                'b_activo'
            ]));

            // Teléfono
            if ($request->has('s_telefono')) {
                $proveedor->s_telefono = preg_replace(
                    '/[^0-9]/',
                    '',
                    $request->s_telefono
                );
            }

            // Imagen
            if ($request->has('s_img_proveedor')) {
                if ($request->s_img_proveedor === null) {
                    // No cambiar imagen
                } elseif (!empty($request->s_img_proveedor)) {
                    if (
                        $proveedor->s_img_proveedor &&
                        $proveedor->s_img_proveedor !== 'proveedor-default.png'
                    ) {
                        $this->deleteProveedorImage($proveedor->s_img_proveedor);
                    }

                    $proveedor->s_img_proveedor = $this->saveBase64ImageProveedor(
                        $request->s_img_proveedor,
                        'proveedor_' . $id_proveedor . '_'
                    );
                }
            }

            $proveedor->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Proveedor actualizado con éxito',
                'data' => $proveedor
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar proveedor',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    private function saveBase64ImageProveedor($base64Image, $prefix = 'proveedor_')
    {
        if (empty($base64Image)) {
            return 'proveedor-default.png';
        }

        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            return 'proveedor-default.png';
        }

        $extension = strtolower($matches[1]);
        $allowedExtensions = ['png', 'jpg', 'jpeg'];

        if (!in_array($extension, $allowedExtensions)) {
            return 'proveedor-default.png';
        }

        try {
            $imageData = base64_decode(
                preg_replace('/^data:image\/\w+;base64,/', '', $base64Image)
            );

            $directory = public_path('proveedores');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $imageName = rtrim($prefix, '_') . '_' . time() . '.' . $extension;
            $filePath = $directory . '/' . $imageName;

            if (file_put_contents($filePath, $imageData)) {
                return $imageName;
            }

            return 'proveedor-default.png';
        } catch (\Exception $e) {
            return 'proveedor-default.png';
        }
    }

    private function deleteProveedorImage($imageName)
    {
        if ($imageName && $imageName !== 'proveedor-default.png') {
            $filePath = public_path('proveedores/' . $imageName);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
    }
}

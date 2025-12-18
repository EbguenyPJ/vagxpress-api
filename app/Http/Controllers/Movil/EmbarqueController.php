<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Embarque;
use App\Models\EvidenciaEmbarque;
use App\Models\EntradaEmbarque;
use App\Models\PreRegistroRefaccion;
use App\Models\Refaccion;
use App\Helpers\ComprimirImagenHelper;
use Illuminate\Support\Facades\File;

class EmbarqueController extends Controller
{
    //
    public function crearEmbarque(Request $request){
        try{
            return DB::transaction(function () use ($request) {

                /* Registramos Embarque */
                $embarque = new Embarque();
                $embarque->id_proveedor = $request->id_proveedor;
                $embarque->d_fecha_creacion = now();
                $embarque->id_usuario_crea = $request->id_usuario_crea;
                $embarque->id_estatus_embarque = 1;
                $embarque->b_activo = 1;
                $embarque->save();
                /* Registramos Embarque */




                /* Registramos evidencias Generales del Embarque */
                if($request->evidencias){
                    foreach($request->evidencias as $evidencia){
                        $evidenciaEmbarque = new EvidenciaEmbarque();
                        $evidenciaEmbarque->id_embarque = $embarque->id_embarque;
                        $evidenciaEmbarque->id_tipo_evidencia = 1;
                        $evidenciaEmbarque->s_evidencia_embarque = 'temp_name';
                        $evidenciaEmbarque->d_fecha_creacion = now();
                        $evidenciaEmbarque->b_activo = 1;
                        $evidenciaEmbarque->save();



                        $imagenBase64 = $evidencia['imagen'];
                        $extension = 'jpg';
                        $nombreFinal = 'PE_EM' . $embarque->id_embarque . '_TE1_' . $evidenciaEmbarque->id_evidencia_embarque . '.' . $extension;
                        $carpeta = 'evidenciasVXM/imgGeneralesEmbarque';
                        // llamar al servicio del helper
                        $comprimir = ComprimirImagenHelper::comprimirImagenBase64(
                            $imagenBase64, // imagen
                            $carpeta,      // carpeta
                            $nombreFinal,  // nombre de la imagen 
                        );
                        // Validar si la imagen se creó correctamente
                        if ($comprimir['status'] === 'success') {
                            // Continuar el flujo normal
                            $evidenciaEmbarque->s_evidencia_embarque = $nombreFinal;
                            $evidenciaEmbarque->save();
                        }
                        else{
                            return response()->json([
                                'status' => 'error',
                                'code' => 400,
                                'message' => 'Hubo un error al comprimir la imagen'
                            ], 400);
                        }
                    }
                }
                /* Registramos evidencias Generales del Embarque */






                /* Registramos la factura del Embarque */
                if($request->factura){
                    $exten = strtolower($request->extension);
                    if(in_array($exten, ['jpg', 'jpeg', 'png'])){
                        $facturaEmbarque = new EvidenciaEmbarque();
                        $facturaEmbarque->id_embarque = $embarque->id_embarque;
                        $facturaEmbarque->id_tipo_evidencia = 2;
                        $facturaEmbarque->s_evidencia_embarque = 'temp_name';
                        $facturaEmbarque->d_fecha_creacion = now();
                        $facturaEmbarque->b_activo = 1;
                        $facturaEmbarque->save();


                        $imagenBase64 = $request->factura;
                        $extension = 'jpg';
                        $nombreFinal = 'PE_EM' . $embarque->id_embarque . '_TE2_' . $facturaEmbarque->id_evidencia_embarque . '.' . $extension;
                        $carpeta = 'evidenciasVXM/imgFacturaEmbarque';
                        // llamar al servicio del helper
                        $comprimir = ComprimirImagenHelper::comprimirImagenBase64(
                            $imagenBase64, // imagen
                            $carpeta,      // carpeta
                            $nombreFinal,  // nombre de la imagen 
                        );
                        // Validar si la imagen se creó correctamente
                        if ($comprimir['status'] === 'success') {
                            // Continuar el flujo normal
                            $facturaEmbarque->s_evidencia_embarque = $nombreFinal;
                            $facturaEmbarque->save();
                        }
                        else{
                            return response()->json([
                                'status' => 'error',
                                'code' => 400,
                                'message' => 'Hubo un error al comprimir la imagen'
                            ], 400);
                        }
                    }elseif($exten === 'pdf'){
                        $facturaEmbarque = new EvidenciaEmbarque();
                        $facturaEmbarque->id_embarque = $embarque->id_embarque;
                        $facturaEmbarque->id_tipo_evidencia = 3;
                        $facturaEmbarque->s_evidencia_embarque = 'temp_name';
                        $facturaEmbarque->d_fecha_creacion = now();
                        $facturaEmbarque->b_activo = 1;
                        $facturaEmbarque->save();


                        $pdfBase64 = $request->factura;
                        $extension = 'pdf';
                        $nombreFinal = 'PE_EM' . $embarque->id_embarque . '_TE3_' . $facturaEmbarque->id_evidencia_embarque . '.' . $extension;
                        $carpeta = 'evidenciasVXM/pdfFacturaEmbarque';


                        $base64Str = preg_replace('/^data:application\/pdf;base64,/', '', $pdfBase64);
                        $base64Str = str_replace(' ', '+', $base64Str); // Corrige posibles espacios
                        $archivoBinario = base64_decode($base64Str);
                        \File::put(public_path($carpeta) . '/' . $nombreFinal, $archivoBinario);
                        
                        $facturaEmbarque->s_evidencia_embarque = $nombreFinal;
                        $facturaEmbarque->save();
                    }   
                    
                }
                /* Registramos la factura del Embarque */




                /* Registramos las entradas */
                foreach($request->entradas as $entrada){

                    if(!empty($entrada['id_refaccion'])){
                        $entradaEmbarque = new EntradaEmbarque();
                        $entradaEmbarque->id_embarque = $embarque->id_embarque;
                        $entradaEmbarque->id_refaccion = $entrada['id_refaccion'];
                        $entradaEmbarque->id_pre_registro_refaccion = null;
                        $entradaEmbarque->id_estatus_entrada = 1;
                        $entradaEmbarque->n_cantidad = $entrada['n_cantidad_recibida'];
                        $entradaEmbarque->n_precio_compra = $entrada['n_precio_compra'];
                        $entradaEmbarque->s_codigo_barras = $entrada['codigo_barras'];
                        $entradaEmbarque->d_fecha_creacion = now();
                        $entradaEmbarque->b_activo = 1;
                        $entradaEmbarque->save();
                    }else{
                        $refaccion = new PreRegistroRefaccion();
                        $refaccion->s_nombre_refaccion = $entrada['s_nombre_refaccion'];
                        $refaccion->s_numero_parte = $entrada['s_numero_parte'];
                        $refaccion->id_marca_refaccion = $entrada['id_marca_refaccion'];
                        $refaccion->id_categoria_refaccion = $entrada['id_categoria_refaccion'];
                        $refaccion->id_subcategoria_refaccion = $entrada['id_subcategoria_refaccion'];
                        $refaccion->id_clase_refaccion = $entrada['id_clase_refaccion'];
                        $refaccion->n_precio_compra = $entrada['n_precio_compra'];
                        $refaccion->id_usuario_crea = $request->id_usuario_crea;
                        $refaccion->save();


                        $entradaEmbarque = new EntradaEmbarque();
                        $entradaEmbarque->id_embarque = $embarque->id_embarque;
                        $entradaEmbarque->id_refaccion = null;
                        $entradaEmbarque->id_pre_registro_refaccion = $refaccion->id_pre_registro_refaccion;
                        $entradaEmbarque->id_estatus_entrada = 1;
                        $entradaEmbarque->n_cantidad = $entrada['n_cantidad_recibida'];
                        $entradaEmbarque->n_precio_compra = $entrada['n_precio_compra'];
                        $entradaEmbarque->s_codigo_barras = $entrada['codigo_barras'];
                        $entradaEmbarque->d_fecha_creacion = now();
                        $entradaEmbarque->b_activo = 1;
                        $entradaEmbarque->save();
                    }

                    

                }
                /* Registramos las entradas */


                // Respuesta de éxito
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Embarque creado exitosamente.'
                ], 200);

            });
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAllEmbarques(){
        try{
             $data = DB::table('tw_embarques as T1')
                ->leftjoin('tw_proveedores as T2', 'T2.id_proveedor', '=', 'T1.id_proveedor')
                ->leftjoin('users as T3', 'T3.id', '=', 'T1.id_usuario_crea')
                ->leftjoin('tc_estatus_embarque as T4', 'T4.id_estatus_embarque', '=', 'T1.id_estatus_embarque')
                ->where('T1.b_activo', 1)
                ->select(
                    'T1.id_embarque',
                    'T1.id_proveedor',
                    'T2.s_proveedor',
                    'T1.d_fecha_creacion',
                    'T1.id_usuario_crea',
                    'T3.s_nombre_completo',
                    'T1.id_estatus_embarque',
                    'T4.s_estatus_embarque'
                )
                ->orderBy('T1.id_embarque', 'desc')
                ->get();


            // Respuesta de éxito
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $data,
                'message' => 'Embarques obtenidos.'
            ], 200);

            
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEmbarque($id_embarque){
        try{

            $data = DB::table('tw_embarques as T1')
                ->leftjoin('tw_proveedores as T2', 'T2.id_proveedor', '=', 'T1.id_proveedor')
                ->leftjoin('users as T3', 'T3.id', '=', 'T1.id_usuario_crea')
                ->leftjoin('tc_estatus_embarque as T4', 'T4.id_estatus_embarque', '=', 'T1.id_estatus_embarque')
                ->where('T1.b_activo', 1)
                ->where('T1.id_embarque', $id_embarque)
                ->select(
                    'T1.id_embarque',
                    'T1.id_proveedor',
                    'T2.s_proveedor',
                    'T1.d_fecha_creacion',
                    'T1.id_usuario_crea',
                    'T3.s_nombre_completo',
                    'T1.id_estatus_embarque',
                    'T4.s_estatus_embarque'
                )
                ->first();


            $entradas = DB::table('tr_entradas_embarque as T1')
                ->leftjoin('tw_refacciones as T2', 'T2.id_refaccion', '=', 'T1.id_refaccion')
                ->leftjoin('tc_marcas_refacciones as T3', 'T3.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                ->leftjoin('tc_categorias_refacciones as T4', 'T4.id_categoria_refaccion', '=', 'T2.id_categoria_refaccion')
                ->leftjoin('tc_subcategorias_refacciones as T5', 'T5.id_subcategoria_refaccion', '=', 'T2.id_subcategoria_refaccion')
                ->leftjoin('tc_clases_refacciones as T6', 'T6.id_clase_refaccion', '=', 'T2.id_clase_refaccion')
                ->select(
                    'T1.id_entrada_embarque',
                    'T1.id_embarque',
                    'T1.id_refaccion',
                    'T2.s_nombre_refaccion',
                    'T3.s_marca_refaccion',
                    'T4.s_categoria_refaccion',
                    'T5.s_subcategoria_refaccion',
                    'T6.s_clase_refaccion',
                    'T2.s_numero_parte',
                    'T1.n_cantidad',
                    'T1.n_precio_compra',
                    'T1.s_codigo_barras',
                    'T1.d_fecha_creacion',
                )
                ->where('T1.id_embarque', $id_embarque)
                ->where('T1.b_activo', 1)
                ->whereNotNull('T1.id_refaccion')
                ->get();

            $pendientes = DB::table('tr_entradas_embarque as T1')
                ->leftjoin('tw_pre_registro_refacciones as T2', 'T2.id_pre_registro_refaccion', '=', 'T1.id_pre_registro_refaccion')
                ->leftjoin('users as T3', 'T3.id', '=', 'T2.id_usuario_crea')
                ->leftjoin('tc_marcas_refacciones as T4', 'T4.id_marca_refaccion', '=', 'T2.id_marca_refaccion')
                ->leftjoin('tc_categorias_refacciones as T5', 'T5.id_categoria_refaccion', '=', 'T2.id_categoria_refaccion')
                ->leftjoin('tc_subcategorias_refacciones as T6', 'T6.id_subcategoria_refaccion', '=', 'T2.id_subcategoria_refaccion')
                ->leftjoin('tc_clases_refacciones as T7', 'T7.id_clase_refaccion', '=', 'T2.id_clase_refaccion')
                ->select(
                    'T1.id_entrada_embarque',
                    'T1.id_embarque',
                    'T1.id_pre_registro_refaccion',
                    'T1.n_cantidad',
                    'T1.n_precio_compra',
                    'T1.s_codigo_barras',
                    'T1.d_fecha_creacion',
                    'T2.s_nombre_refaccion',
                    'T2.s_numero_parte',
                    'T4.id_marca_refaccion',
                    'T4.s_marca_refaccion',
                    'T5.id_categoria_refaccion',
                    'T5.s_categoria_refaccion',
                    'T6.id_subcategoria_refaccion',
                    'T6.s_subcategoria_refaccion',
                    'T7.id_clase_refaccion',
                    'T7.s_clase_refaccion',
                    'T2.n_precio_compra',
                    'T2.id_usuario_crea',
                    'T3.s_nombre_completo'
                )
                ->where('T1.id_embarque', $id_embarque)
                ->where('T1.b_activo', 1)
                ->whereNotNull('T1.id_pre_registro_refaccion')
                ->get();
            
            $factura = DB::table('tw_evidencias_embarque as T1')
                ->select(
                    'T1.s_evidencia_embarque',
                    'T1.id_tipo_evidencia'
                )
                ->where('T1.id_embarque', $id_embarque)
                ->where('T1.id_tipo_evidencia', 3)
                ->where('T1.b_activo', 1)
                ->first();
            
            if($factura && $factura->id_tipo_evidencia === 3){
                $path = public_path("evidenciasVXM/pdfFacturaEmbarque/$factura->s_evidencia_embarque");
                $base64 = base64_encode(File::get($path));
            }else{
                $factura = DB::table('tw_evidencias_embarque as T1')
                ->select(
                    'T1.s_evidencia_embarque',
                    'T1.id_tipo_evidencia'
                )
                ->where('T1.id_embarque', $id_embarque)
                ->where('T1.id_tipo_evidencia', 2)
                ->where('T1.b_activo', 1)
                ->first();

                $base64 = '';
            }


            
            
            $result = [
                'embarque' => $data,
                'entradas' => $entradas,
                'pendientes' => $pendientes,
                'factura' => $factura,
                'base64' => $base64
            ];


            // Respuesta de éxito
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $result,
                'message' => 'Embarque obtenido.'
            ], 200);
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function aprobarEmbarque(Request $request, $id_embarque){
        try{
            return DB::transaction(function () use ($request, $id_embarque) {

                
                /* Si la refacción es nueva se agrega a tw_refacciones */
                $pendientes = $request->pendientes;
                if($pendientes){
                    foreach($pendientes as $pendiente){
                        $refaccion = new Refaccion();
                        $refaccion->s_nombre_refaccion = $pendiente['s_nombre_refaccion'];
                        $refaccion->s_numero_parte = $pendiente['s_numero_parte'];
                        $refaccion->id_marca_refaccion = $pendiente['id_marca_refaccion'];
                        $refaccion->id_categoria_refaccion = $pendiente['id_categoria_refaccion'];
                        $refaccion->id_subcategoria_refaccion = $pendiente['id_subcategoria_refaccion'];
                        $refaccion->id_clase_refaccion = $pendiente['id_clase_refaccion'];
                        $refaccion->n_precio_compra = $pendiente['n_precio_compra'];
                        $refaccion->id_usuario_crea = $request->id_usuario;
                        $refaccion->save();
                    }
                }
                /* Si la refacción es nueva se agrega a tw_refacciones */



                /* Si la refacción ya existe se actualiza campos en  tw_refacciones */
                $entradas = $request->entradas;
                if($entradas){
                    foreach($entradas as $entrada){
                        $refaccion = Refaccion::find($entrada['id_refaccion']);
                        $refaccion->n_precio_compra = $entrada['n_precio_compra'];
                        $refaccion->n_stock_actual = $refaccion->n_stock_actual + $entrada['n_cantidad'];
                        $refaccion->id_usuario_edita = $request->id_usuario;
                        $refaccion->save();
                    }
                }
                /* Si la refacción ya existe se actualiza campos en  tw_refacciones */





                /* Buscamos el embarque y lo aprobamos */
                $embarque = Embarque::find($id_embarque);
                $embarque->id_estatus_embarque = 2;
                $embarque->save();
                /* Buscamos el embarque y lo aprobamos */



                /* Buscamos las entradas y actualizamos su estatus a aprobado*/
                EntradaEmbarque::where('id_embarque', $id_embarque)
                ->update([
                    'id_estatus_entrada' => 2
                ]);
                /* Buscamos las entradas y actualizamos su estatus a aprobado*/


                // Respuesta de éxito
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Embarque aprobado exitosamente.'
                ], 200);

            });
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function rechazarEmbarque(Request $request, $id_embarque){
        try{
            return DB::transaction(function () use ($request, $id_embarque) {

                
                /* Buscamos el embarque y lo rechazamos */
                $embarque = Embarque::find($id_embarque);
                $embarque->id_estatus_embarque = 3;
                $embarque->save();
                /* Buscamos el embarque y lo rechazamos */



                /* Buscamos las entradas y actualizamos su estatus a rechazado*/
                EntradaEmbarque::where('id_embarque', $id_embarque)
                ->update([
                    'id_estatus_entrada' => 3
                ]);
                /* Buscamos las entradas y actualizamos su estatus a rechazado*/


                // Respuesta de éxito
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Embarque rechazado exitosamente.'
                ], 200);

            });
        }catch (Exception $e) {
            // Respuesta de error
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

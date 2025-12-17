<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Embarque;
use App\Models\EvidenciaEmbarque;
use App\Models\EntradaEmbarque;
use App\Models\PreRegistroRefaccion;
use App\Helpers\ComprimirImagenHelper;

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
}

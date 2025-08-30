<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\AlertaHelper;

// Importamos el modelo Version;
use App\Models\Version;



class TestController extends Controller
{

    public function crearQr(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if($data)
        {
            $contenido_qr = $data['s_contenido_qr'];
            $s_nombre_qr = $data['s_nombre_qr'];

            $ruta_qr = '../public/qrs_prueba/qr_' . $s_nombre_qr . '.png';
            QrCode::format('png')->size(300)->encoding('UTF-8')->generate($contenido_qr, $ruta_qr);

            // llamar al servicio del helper
             $alertResult = AlertaHelper::crearAlerta(
                 3, // id_tipo_alerta
                 'Corrección de hora en linea', // s_alerta
                 'Creando alerta para comprobar el css latente' // s_descripcion (opcional)
             );
                 // Validar si la alerta se creó correctamente
             if ($alertResult['status'] === 'success') {
                 // Continuar el flujo normal
                 return response() -> json(['message'=>'Código QR creado con exito..!!'], 200);
             }
             else{
                 // Manejar el error de creación de alerta
                 return response()->json(['message' => 'Error al crear alerta: ' . $alertResult['error']], 500);
             }
            // end helper alertas
        }
        else
        {
            return response()->json(['message'=>'Error al crear QR..!!'], 500);
        }

    }




    public function subirImagenCloudinary(Request $request)
    {
        try {
            // Sacamos la imagen de la carpeta publi/img
            $path = public_path('img/gas1.png');

            // verificamos que exista
            if (!File::exists($path)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El archivo no existe: ' . $path,
                ], 404);
            }

            // subimos la imagen al cloudinary
            // recuerda tener el .env las credenciales y el config/cloudinary.php
            $uploadedFile = Cloudinary::upload($path, [
                'folder' => 'TallerUP',
                'resource_type' => 'image',
            ]);

            
            $url = $uploadedFile->getSecurePath();

            return response()->json([
                'status'  => 'success',
                'message' => 'Imagen subida a Cloudinary',
                'url'     => $url,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


}

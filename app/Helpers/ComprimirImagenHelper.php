<?php

namespace App\Helpers;
use Image;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Importamos el modelo Alerta;
use App\Models\Alerta;


use Illuminate\Support\Carbon;

class ComprimirImagenHelper
{
    public static function comprimirImagen($imagen, $carpeta, $nombre)
    {
        // Validación de los datos
        if (empty($imagen)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'la imagen es obligatoria.',
            ]; 
        }

        // Validación de los datos
        if (empty($carpeta)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'la carpeta es obligatoria.',
            ]; 
        }


        if (empty($nombre)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'el nombre es obligatorio.',
            ]; 
        }

        try{
            if ($imagen->isValid()) {
                //$nombre = time() . '.' . $imagen->getClientOriginalExtension();

                // Comprimir y redimensionar (si es necesario)
                $imagenRedimensionada = Image::make($imagen)
                    ->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 70); // 70% de calidad, menor peso

                \File::put(public_path($carpeta) . '/' . $nombre, $imagenRedimensionada);

                // Respuesta de exito
                return [
                    'status'  => 'success',
                    'code'    => 200,
                    'message' => 'Imagen comprimida y guardada correctamente',
                    'data'    => $imagenRedimensionada
                ];
            }

            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'No es una imagen',
                'data'    => $imagen
            ];

        }catch (QueryException $e) {
            // Respuesta de error
            return [
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al comprimir la imagen.',
                'error'   => $e->getMessage(),
            ];
        }
    }





    public static function comprimirImagenBase64($imagen, $carpeta, $nombre)
    {
        // Validación de los datos
        if (empty($imagen)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'la imagen es obligatoria.',
            ]; 
        }

        // Validación de los datos
        if (empty($carpeta)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'la carpeta es obligatoria.',
            ]; 
        }

        if (empty($nombre)) {
            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'el nombre es obligatorio.',
            ]; 
        }

        try{
            if ($imagen) {
                $base64Str = preg_replace('/^data:image\/\w+;base64,/', '', $imagen);
                $base64Str = str_replace(' ', '+', $base64Str); // Corrige posibles espacios
                $archivoBinario = base64_decode($base64Str);


                // Comprimir y redimensionar (si es necesario)
                $imagenRedimensionada = Image::make($archivoBinario)
                    ->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 70); // 70% de calidad, menor peso

                \File::put(public_path($carpeta) . '/' . $nombre, $imagenRedimensionada);

                // Respuesta de exito
                return [
                    'status'  => 'success',
                    'code'    => 200,
                    'message' => 'Imagen comprimida y guardada correctamente',
                    'data'    => $imagenRedimensionada
                ];
            }

            return [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'No es una imagen',
                'data'    => $imagen
            ];

        }catch (QueryException $e) {
            // Respuesta de error
            return [
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error al comprimir la imagen.',
                'error'   => $e->getMessage(),
            ];
        }
    }
}

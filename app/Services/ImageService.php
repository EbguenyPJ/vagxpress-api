<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

/**
 * Almacenamiento de imágenes base64 en public/{directorio}.
 * Centraliza la validación de formato y el ciclo de reemplazo.
 */
class ImageService
{
    private const EXTENSIONES_PERMITIDAS = ['png', 'jpg', 'jpeg'];

    /**
     * Guarda una imagen base64 y devuelve el nombre de archivo,
     * o $porDefecto si el contenido no es una imagen válida.
     */
    public function guardarBase64(?string $base64, string $directorio, string $prefijo, ?string $porDefecto = null): ?string
    {
        if (empty($base64) || ! preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
            return $porDefecto;
        }

        $extension = strtolower($matches[1]);
        if (! in_array($extension, self::EXTENSIONES_PERMITIDAS)) {
            return $porDefecto;
        }

        $contenido = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64), true);
        if ($contenido === false) {
            return $porDefecto;
        }

        $ruta = public_path($directorio);
        File::ensureDirectoryExists($ruta);

        $nombre = rtrim($prefijo, '_') . '_' . time() . '.' . $extension;

        return file_put_contents("$ruta/$nombre", $contenido) !== false ? $nombre : $porDefecto;
    }

    /**
     * Guarda una imagen base64 comprimida (máx. 1280px de ancho, JPG 70%).
     * Devuelve true si se guardó; false si el contenido no es una imagen.
     */
    public function guardarBase64Comprimida(?string $base64, string $directorio, string $nombre): bool
    {
        if (empty($base64)) {
            return false;
        }

        try {
            $imagen = Image::make($base64)
                ->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 70);
        } catch (\Throwable) {
            return false;
        }

        File::ensureDirectoryExists(public_path($directorio));
        File::put(public_path($directorio) . '/' . $nombre, $imagen);

        return true;
    }

    /** Guarda un PDF base64 tal cual (sin recomprimir). */
    public function guardarPdfBase64(string $base64, string $directorio, string $nombre): void
    {
        $limpio = preg_replace('/^data:application\/pdf;base64,/', '', $base64);
        $limpio = str_replace(' ', '+', $limpio);

        File::ensureDirectoryExists(public_path($directorio));
        File::put(public_path($directorio) . '/' . $nombre, base64_decode($limpio));
    }

    public function eliminar(string $directorio, ?string $nombre, ?string $porDefecto = null): void
    {
        if (! $nombre || $nombre === $porDefecto) {
            return;
        }

        $ruta = public_path("$directorio/$nombre");
        if (File::exists($ruta)) {
            File::delete($ruta);
        }
    }
}

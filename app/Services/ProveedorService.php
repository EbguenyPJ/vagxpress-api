<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProveedorService
{
    private const DIRECTORIO_IMAGENES = 'proveedores';
    private const IMAGEN_POR_DEFECTO = 'proveedor-default.png';

    public function __construct(private readonly ImageService $imageService)
    {
    }

    public function listar(): Collection
    {
        return Proveedor::activo()->get();
    }

    public function crear(array $datos): Proveedor
    {
        if (Proveedor::where('s_proveedor', $datos['s_proveedor'])->where('s_rfc', $datos['s_rfc'] ?? null)->exists()) {
            throw new DomainException('Ya existe un proveedor con estos datos');
        }

        return DB::transaction(function () use ($datos) {
            $proveedor = Proveedor::create([
                's_proveedor' => $datos['s_proveedor'],
                's_nombre_contacto' => $datos['s_nombre_contacto'] ?? null,
                's_telefono' => $this->limpiarTelefono($datos['s_telefono'] ?? null),
                's_rfc' => $datos['s_rfc'] ?? null,
                'b_activo' => 1,
                's_img_proveedor' => self::IMAGEN_POR_DEFECTO,
            ]);

            $this->guardarImagen($proveedor, $datos['s_img_proveedor'] ?? null);

            return $proveedor;
        });
    }

    public function actualizar(int $idProveedor, array $datos): Proveedor
    {
        $proveedor = Proveedor::findOrFail($idProveedor);

        $proveedor->fill(array_intersect_key($datos, array_flip(['s_proveedor', 's_nombre_contacto', 's_rfc'])));

        if (array_key_exists('s_telefono', $datos)) {
            $proveedor->s_telefono = $this->limpiarTelefono($datos['s_telefono']);
        }

        if (! empty($datos['s_img_proveedor'])) {
            $this->imageService->eliminar(self::DIRECTORIO_IMAGENES, $proveedor->s_img_proveedor, self::IMAGEN_POR_DEFECTO);
            $this->guardarImagen($proveedor, $datos['s_img_proveedor']);
        }

        $proveedor->save();

        return $proveedor;
    }

    private function guardarImagen(Proveedor $proveedor, ?string $base64): void
    {
        $nombre = $this->imageService->guardarBase64(
            $base64,
            self::DIRECTORIO_IMAGENES,
            "proveedor_{$proveedor->id_proveedor}_",
            self::IMAGEN_POR_DEFECTO,
        );

        if ($nombre !== $proveedor->s_img_proveedor) {
            $proveedor->update(['s_img_proveedor' => $nombre]);
        }
    }

    private function limpiarTelefono(?string $telefono): ?string
    {
        return $telefono === null ? null : (preg_replace('/[^0-9]/', '', $telefono) ?: null);
    }
}

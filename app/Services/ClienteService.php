<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    /** Listado completo para la pantalla de clientes. */
    public function listar(): Collection
    {
        return Cliente::activo()
            ->with('tipoCliente')
            ->orderBy('id_cliente')
            ->get();
    }

    /** Selector ligero para el punto de venta: nombre y crédito disponible. */
    public function selector()
    {
        return Cliente::activo()
            ->select('id_cliente', 's_nombre_cliente', DB::raw('(n_limite_credito - n_saldo_actual) AS saldo_actual'))
            ->get();
    }

    public function crear(array $datos, int $idUsuario): Cliente
    {
        return Cliente::create($datos + [
            'id_usuario_crea' => $idUsuario,
            'n_saldo_actual' => 0,
            'n_limite_credito' => $datos['n_limite_credito'] ?? 0,
            'b_credito' => $datos['b_credito'] ?? 0,
            'b_activo' => 1,
        ]);
    }

    public function actualizar(int $idCliente, array $datos, int $idUsuario): Cliente
    {
        $cliente = Cliente::findOrFail($idCliente);
        $cliente->update($datos + ['id_usuario_modifica' => $idUsuario]);

        return $cliente;
    }
}

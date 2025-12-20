<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificarStockBajo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idsRefacciones;
    public $idUsuario;


     //Create a new event instance.

    public function __construct(array $idsRefacciones, int $idUsuario)
    {
        $this->idsRefacciones = $idsRefacciones;
        $this->idUsuario = $idUsuario;
    }
}

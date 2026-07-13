<?php

namespace App\Exceptions;

use Exception;

/**
 * Error de regla de negocio: mensaje seguro para el cliente y
 * código HTTP semántico (409 por defecto).
 */
class DomainException extends Exception
{
    public function __construct(string $message, private readonly int $status = 409)
    {
        parent::__construct($message);
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}

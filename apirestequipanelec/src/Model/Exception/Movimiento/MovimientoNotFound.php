<?php

namespace App\Model\Exception\Movimiento;

use Exception;

class MovimientoNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Movimiento no Encontrado');
    }
}
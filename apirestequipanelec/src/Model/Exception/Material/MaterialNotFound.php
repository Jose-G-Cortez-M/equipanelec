<?php

namespace App\Model\Exception\Material;

use Exception;

class MaterialNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Material no Encontrado');
    }
}
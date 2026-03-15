<?php

namespace App\Enums;

enum Estado: String
{
    case FUERA = "fuera";
    case EN_CLASE = "en clase";

    public static function values(): array {
        return array_column(self::cases(), "value");
    }
    
}
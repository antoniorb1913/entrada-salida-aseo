<?php

namespace App\Enums;

enum Genero: String
{
    case MASCULINO = "MASCULINO";
    case FEMENINO = "FEMENINO";

    public static function values(): array {
        return array_column(self::cases(), "value");
    }
}

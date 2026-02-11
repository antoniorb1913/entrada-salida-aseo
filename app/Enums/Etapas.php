<?php

namespace App\Enums;

enum Etapas: String
{
    case ESO = "ESO";
    case BACHILLERATO = "BACHILLERATO";
    case FP = "FP";

    public static function values(): array {
        return array_column(self::cases(), "value");
    }
    
}

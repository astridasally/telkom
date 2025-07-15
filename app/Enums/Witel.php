<?php

namespace App\Enums;

enum Witel: string
{
    case WITEL_1 = 'Witel 1';
    case WITEL_2 = 'Witel 2';
    case WITEL_3 = 'Witel 3';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

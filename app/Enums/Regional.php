<?php

namespace App\Enums;

enum Regional: string
{
    case REGIONAL_1 = 'Regional 1';
    case REGIONAL_2 = 'Regional 2';
    case REGIONAL_3 = 'Regional 3';
    case REGIONAL_4 = 'Regional 4';
    case REGIONAL_5 = 'Regional 5';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Enums;


enum Regional: string
{
    case REGIONAL1 = 'Regional1';
    case REGIONAL2 = 'Regional2';
    case REGIONAL3 = 'Regional3';
    case REGIONAL4 = 'Regional4';
    case REGIONAL5 = 'Regional5';

    public static function witels(): array
    {
        return [
            self::REGIONAL1->value => ['ACEH', 'SUMUT', 'RIAU', 'KEPRI'],
            self::REGIONAL2->value => ['JAKARTA BARAT', 'JAKARTA TIMUR', 'BODETABEK', 'BANTEN'],
            self::REGIONAL3->value => ['BANDUNG', 'CIREBON', 'BOGOR', 'CIAMIS'],
            self::REGIONAL4->value => ['JAWA TENGAH', 'DIY', 'KUDUS', 'MAGELANG', 'SOLO'],
            self::REGIONAL5->value => ['BALI', 'NTB', 'NTT', 'MALUKU', 'PAPUA']
        ];
    }

}
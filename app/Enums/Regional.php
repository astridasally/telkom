<?php

namespace App\Enums;


enum Regional: string
{
    case REGIONAL_1 = 'Regional 1';
    case REGIONAL_2 = 'Regional 2';
    case REGIONAL_3 = 'Regional 3';
    case REGIONAL_4 = 'Regional 4';
    case REGIONAL_5 = 'Regional 5';

    public static function witels(): array
    {
        return [
            self::REGIONAL_1->value => ['ACEH', 'LAMPUNG BENGKULU', 'RIAU', 'SUMBAGSEL', 'SUMBAR JAMBI','SUMUT'],
            self::REGIONAL_2->value => ['BANDUNG','BANTEN','BEKASI KARAWANG','JAKARTA CENTRUM','JAKARTA INNER','JAKARTA OUTER', 'PRIANGAN BARAT', 'PRIANGAN TIMUR'],
            self::REGIONAL_3->value => ['BALI','JATIM BARAT','JATIM TIMUR','NUSA TENGGARA','SEMARANG JATENG UTARA','SOLO JATENG TIMUR','SURAMADU','YOGYA JATENG SELATAN'],
            self::REGIONAL_4->value => ['BALIKPAPAN','KALBAR','KALSELTENG','KALTIMTARA'],
            self::REGIONAL_5->value => ['PAPUA','PAPUA BARAT','SULBAGSEL','SULBAGTENG','SUMALUT']
        ];
    }

}
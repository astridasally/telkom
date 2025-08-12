<?php

namespace App\Enums;

enum Witel: string
{
    case ACEH                   = 'ACEH';
    case LAMPUNG_BENGKULU       = 'LAMPUNG BENGKULU';
    case RIAU                   = 'RIAU';
    case SUMBAGSEL              = 'SUMBAGSEL';
    case SUMBAR_JAMBI           = 'SUMBAR JAMBI';
    case SUMUT                  = 'SUMUT';

    case BANDUNG                = 'BANDUNG';
    case BANTEN                 = 'BANTEN';
    case BEKASI_KARAWANG        = 'BEKASI KARAWANG';
    case JAKARTA_CENTRUM        = 'JAKARTA CENTRUM';
    case JAKARTA_INNER          = 'JAKARTA INNER';
    case JAKARTA_OUTER          = 'JAKARTA OUTER';
    case PRIANGAN_BARAT         = 'PRIANGAN BARAT';
    case PRIANGAN_TIMUR         = 'PRIANGAN TIMUR';

    case BALI                   = 'BALI';
    case JATIM_BARAT            = 'JATIM BARAT';
    case JATIM_TIMUR            = 'JATIM TIMUR';
    case NUSA_TENGGARA          = 'NUSA TENGGARA';
    case SEMARANG_JATENG_UTARA  = 'SEMARANG JATENG UTARA';
    case SOLO_JATENG_TIMUR      = 'SOLO JATENG TIMUR';
    case SURAMADU               = 'SURAMADU';
    case YOGYA_JATENG_SELATAN   = 'YOGYA JATENG SELATAN';

    case BALIKPAPAN             = 'BALIKPAPAN';
    case KALBAR                 = 'KALBAR';
    case KALSELTENG             = 'KALSELTENG';
    case KALTIMTARA             = 'KALTIMTARA';

    case PAPUA                  = 'PAPUA';
    case PAPUA_BARAT            = 'PAPUA BARAT';
    case SULBAGSEL              = 'SULBAGSEL';
    case SULBAGTENG             = 'SULBAGTENG';
    case SUMALUT                = 'SUMALUT';



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}


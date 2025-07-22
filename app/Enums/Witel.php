<?php

namespace App\Enums;

enum Witel: string
{
    case ACEH = 'ACEH';
    case BALI = 'BALI';
    case BANTEN = 'BANTEN';
    case BABEL = 'BABEL';
    case BANDUNG = 'BANDUNG';
    case BENGKULU = 'BENGKULU';
    case BODETABEK = 'BODETABEK';
    case BOGOR = 'BOGOR';
    case CIAMIS = 'CIAMIS';
    case CIANJUR = 'CIANJUR';
    case CIREBON = 'CIREBON';
    case GORONTALO = 'GORONTALO';
    case JAMBI = 'JAMBI';
    case JAKARTA_BARAT = 'JAKARTA BARAT';
    case JAKARTA_PUSAT = 'JAKARTA PUSAT';
    case JAKARTA_SELATAN = 'JAKARTA SELATAN';
    case JAKARTA_TIMUR = 'JAKARTA TIMUR';
    case JAKARTA_UTARA = 'JAKARTA UTARA';
    case JEMBER = 'JEMBER';
    case KALTARA = 'KALTARA';
    case KALBAR = 'KALBAR';
    case KALSEL = 'KALSEL';
    case KALTENG = 'KALTENG';
    case KALTIM = 'KALTIM';
    case KARAWANG = 'KARAWANG';
    case KEDIRI = 'KEDIRI';
    case KEPRI = 'KEPRI';
    case KUDUS = 'KUDUS';
    case LAMPUNG = 'LAMPUNG';
    case MAGELANG = 'MAGELANG';
    case MALANG = 'MALANG';
    case MALUKU = 'MALUKU';
    case MALUT = 'MALUT';
    case MADIUN = 'MADIUN';
    case NTB = 'NTB';
    case NTT = 'NTT';
    case PAPUA = 'PAPUA';
    case PAPUA_BARAT = 'PAPUA BARAT';
    case PASURUAN = 'PASURUAN';
    case PURWOKERTO = 'PURWOKERTO';
    case RIAU = 'RIAU';
    case SEMARANG = 'SEMARANG';
    case SIDOARJO = 'SIDOARJO';
    case SOLO = 'SOLO';
    case SULBAR = 'SULBAR';
    case SULSEL = 'SULSEL';
    case SULTENG = 'SULTENG';
    case SULTRA = 'SULTRA';
    case SULUT = 'SULUT';
    case SUMBAR = 'SUMBAR';
    case SUMSEL = 'SUMSEL';
    case SUMUT = 'SUMUT';
    case YOGYAKARTA = 'YOGYAKARTA';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}


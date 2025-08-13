<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FunnelingExport implements FromArray, WithHeadings
{
    protected $data;
    protected $project;

    public function __construct(array $data, $project)
    {
        $this->data = $data;
        $this->project = $project;
    }

    public function headings(): array
    {
        return [
            'Regional', 'PLAN CSF', 'FTTH READY', 'JUMLAH P',
            'MOS Plan', 'MOS Done',
            'INSTALASI Plan', 'INSTALASI Done',
            'INTEGRASI Plan', 'INTEGRASI Done',
            'GO LIVE',
            'UPLINK MINI OLT READY', 'UPLINK MINI OLT NOT READY'
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
}


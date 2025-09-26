<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FunnelingDetailExport implements FromArray, WithHeadings
{
    protected $data;
    protected $columns;

    public function __construct(array $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function array(): array
    {
        return $this->data;
    }
}

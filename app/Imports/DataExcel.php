<?php
namespace App\Imports;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DataExcel implements ToModel, WithHeadingRow, WithMapping
{
    public function model(array $row)
    {
        return new Project($row);
    }

    public function map($row): array
    {
        return [
            'user_id'              => auth()->id(),
            'regional'             => $row['regional'],
            'witel'                => $row['witel'],
            'sto'                  => $row['sto'],
            'site'                 => $row['site'],
            'catuan_id'            => $row['catuan_id'] ?? null,
            'ihld'                 => $row['ihld'],
            'plan_survey'          => $this->toDate($row['plan_survey']),
            'realisasi_survey'     => $this->toDate($row['realisasi_survey']),
            'plan_delivery'        => $this->toDate($row['plan_delivery']),
            'realisasi_delivery'   => $this->toDate($row['realisasi_delivery']),
            'plan_instalasi'       => $this->toDate($row['plan_instalasi']),
            'realisasi_instalasi'  => $this->toDate($row['realisasi_instalasi']),
            'plan_integrasi'       => $this->toDate($row['plan_integrasi']),
            'realisasi_integrasi'  => $this->toDate($row['realisasi_integrasi']),
            'drop_data'            => $row['drop_data'] ?? null,
            'bukti_drop'           => $row['bukti_drop'] ?? null,
            'relok_regional'       => $row['relok_regional'] ?? null,
            'relok_witel'          => $row['relok_witel'] ?? null,
            'relok_sto'            => $row['relok_sto'] ?? null,
            'relok_site'           => $row['relok_site'] ?? null,
            'remark'               => $row['remark'] ?? null,
            'priority_ta'          => $row['priority_ta'] ?? null,
            'dependensi'           => $row['dependensi'] ?? null,
            'assign_to'            => $row['assign_to'] ?? null,
            'status_osp'           => $row['status_osp'] ?? null,
            'scenario_uplink'      => $row['scenario_uplink'] ?? null,
            'status_uplink'        => $row['status_uplink'] ?? null,
            'remark_ta'            => $row['remark_ta'] ?? null,
            'jumlah_port'          => $row['jumlah_port'] ?? null,
            'golive_status'        => $row['golive_status'] ?? null,
            'category'             => $row['category'] ?? null,


        ];
    }

    private function toDate($value)
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

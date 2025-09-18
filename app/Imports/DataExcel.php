<?php
namespace App\Imports;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DataExcel implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Project([
            'user_id'             => auth()->id(), // 🔹 Tambahin ini
            'project_type'        => $row['project_type'] ?? null,
            'regional'            => $row['regional'] ?? null,
            'witel'               => $row['witel'] ?? null,
            'sto'                 => $row['sto'] ?? null,
            'site'                => $row['site'] ?? null,
            'ihld'                => $row['ihld'] ?? null,
            'catuan_id'           => $row['catuan_id'] ?? null,
            'assign_to'           => $row['assign_to'] ?? null,
            'category'            => $row['category'] ?? null,

            // Tahapan
            'plan_survey'         => $this->toDate($row['lainnya_plan'] ?? null),
            'realisasi_survey'    => $this->toDate($row['lainnya_realisasi'] ?? null),
            'plan_delivery'       => $this->toDate($row['mos_plan'] ?? null),
            'realisasi_delivery'  => $this->toDate($row['mos_realisasi'] ?? null),
            'plan_instalasi'      => $this->toDate($row['instalasi_plan'] ?? null),
            'realisasi_instalasi' => $this->toDate($row['instalasi_realisasi'] ?? null),
            'plan_integrasi'      => $this->toDate($row['integrasi_plan'] ?? null),
            'realisasi_integrasi' => $this->toDate($row['integrasi_realisasi'] ?? null),

            // Drop & Relokasi
            'drop_data'           => $row['drop'] ?? null,
            'bukti_drop'          => $row['bukti_drop'] ?? null,
            'relok_regional'      => $row['relok_regional'] ?? null,
            'relok_witel'         => $row['relok_witel'] ?? null,
            'relok_sto'           => $row['relok_sto'] ?? null,
            'relok_site'          => $row['relok_site'] ?? null,

            // Info tambahan
            'remark'              => $row['remark'] ?? null,
            'priority_ta'         => $row['priority_ta'] ?? null,
            'dependensi'          => $row['dependensi'] ?? null,
            'jumlah_port'         => $row['jumlah_port'] ?? null,
            'status_osp'          => $row['status_osp'] ?? null,
            'scenario_uplink'     => $row['uplink_skenario'] ?? null,
            'status_uplink'       => $row['uplink_status'] ?? null,
            'remark_ta'           => $row['remark_ta'] ?? null,
            'golive_status'       => $row['golive_status'] ?? null,
        ]);
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

<?php
namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataExcel implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Project([
            'user_id'              => auth()->id(), // atau $row['user_id'] jika ada di Excel
            'regional'             => $row['regional'],
            'witel'                => $row['witel'],
            'sto'                  => $row['sto'],
            'site'                 => $row['site'],
            'priority'             => $row['priority'],
            'catuan_id'            => $row['catuan_id'],
            'ihld'                 => $row['ihld'],
            'plan_survey'          => $row['plan_survey'],
            'realisasi_survey'     => $row['realisasi_survey'],
            'plan_delivery'        => $row['plan_delivery'],
            'realisasi_delivery'   => $row['realisasi_delivery'],
            'plan_instalasi'       => $row['plan_instalasi'],
            'realisasi_instalasi'  => $row['realisasi_instalasi'],
            'plan_integrasi'       => $row['plan_integrasi'],
            'realisasi_integrasi'  => $row['realisasi_integrasi'],
            'drop_data'            => $row['drop_data'],
            'bukti_drop'           => $row['bukti_drop'],
            'relok_regional'       => $row['relok_regional'],
            'relok_witel'          => $row['relok_witel'],
            'relok_sto'            => $row['relok_sto'],
            'relok_site'           => $row['relok_site'],
            'remark'               => $row['remark'],
            'priority_ta'          => $row['priority_ta'],
            'dependensi'           => $row['dependensi'],
            'assign_to'            => $row['assign_to'],
            'status_osp'           => $row['status_osp'],
            'scenario_uplink'      => $row['scenario_uplink'],
            'status_uplink'        => $row['status_uplink'],
            'remark_ta'            => $row['remark_ta'],

        ]);
    }
}


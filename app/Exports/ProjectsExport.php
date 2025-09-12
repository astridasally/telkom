<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type;
    protected $role;

    public function __construct($type)
    {
        $this->type = $type;
        $this->role = Auth::user()->role; // Ambil role user login
    }

    public function collection()
    {
        return Project::query()
            ->when($this->type, fn($q) => $q->where('project_type', $this->type))
            ->with('user')
            ->get();
    }

    public function headings(): array
    {
        $headings = ['No'];

        if (in_array($this->role, ['admin', 'vendor'])) {
            $headings[] = 'Add by';
        }

        $headings = array_merge($headings, [
            'Project Type',
            'Regional',
            'Witel',
            'STO',
            'Site',
            'IHLD',
            'Catuan ID',
        ]);

        if (in_array($this->role, ['mitra', 'admin'])) {
            $headings = array_merge($headings, [
                'Assign To',
                'Category',
                'LAINNYA Plan',
                'LAINNYA Realisasi',
                'MOS Plan',
                'MOS Realisasi',
                'INSTALASI Plan',
                'INSTALASI Realisasi',
                'INTEGRASI Plan',
                'INTEGRASI Realisasi',
                'Drop',
                'Bukti Drop',
                'Relok Regional',
                'Relok Witel',
                'Relok STO',
                'Relok Site',
            ]);
        }

        if ($this->role === 'mitra') {
            $headings[] = 'Status Uplink';
        }

        if (in_array($this->role, ['mitra', 'admin'])) {
            $headings[] = 'Remark';
        }

        if (in_array($this->role, ['vendor', 'admin'])) {
            $headings = array_merge($headings, [
                'Priority TA',
                'Dependensi',
                'Assign to',
                'Jumlah Port',
                'Go Live Status',
                'Status OSP',
                'Uplink Skenario',
                'Uplink Status',
                'Remark TA'
            ]);
        }

        return $headings;
    }

    public function map($project): array
    {
        static $no = 1;
        $row = [$no++];

        if (in_array($this->role, ['admin', 'vendor'])) {
            $row[] = $project->user->name ?? '';
        }

        $row = array_merge($row, [
            $project->project_type,
            $project->regional,
            $project->witel,
            $project->sto,
            $project->site,
            $project->ihld,
            $project->catuan_id,
        ]);

        if (in_array($this->role, ['mitra', 'admin'])) {
            $row = array_merge($row, [
                $project->assign_to,
                $project->category,
                $project->plan_survey,
                $project->realisasi_survey,
                $project->plan_delivery,
                $project->realisasi_delivery,
                $project->plan_instalasi,
                $project->realisasi_instalasi,
                $project->plan_integrasi,
                $project->realisasi_integrasi,
                $project->drop_data,
                $project->bukti_drop ? asset('storage/' . $project->bukti_drop) : '-',
                $project->relok_regional,
                $project->relok_witel,
                $project->relok_sto,
                $project->relok_site,
            ]);
        }

        if ($this->role === 'mitra') {
            $row[] = $project->status_uplink;
        }

        if (in_array($this->role, ['mitra', 'admin'])) {
            $row[] = $project->remark;
        }

        if (in_array($this->role, ['vendor', 'admin'])) {
            $row = array_merge($row, [
                $project->priority_ta,
                $project->dependensi,
                $project->assign_to,
                $project->jumlah_port,
                $project->golive_status,
                $project->status_osp,
                $project->scenario_uplink,
                $project->status_uplink,
                $project->remark_ta,
            ]);
        }

        return $row;
    }
}

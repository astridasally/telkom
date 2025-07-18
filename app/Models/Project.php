<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'regional',
        'witel',
        'sto',
        'site',
        'priority',
        'catuan_id',
        'ihld',
        'plan_survey',
        'realisasi_survey',
        'plan_delivery',
        'realisasi_delivery',
        'plan_instalasi',
        'realisasi_instalasi',
        'plan_integrasi',
        'realisasi_integrasi',
        'drop_data',
        'bukti_drop',
        'relok_regional',
        'relok_witel',
        'relok_sto',
        'relok_site',
        'remark',
        'priority_ta',
        'status_osp',
        'dependensi',
        'scenario_uplink',
        'status_uplink',
        'jumlah_port',
        'drop_ta',
        'remark_ta',

        'user_id', // relasi ke user
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

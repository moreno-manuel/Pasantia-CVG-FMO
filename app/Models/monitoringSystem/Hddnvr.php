<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Model;

class Hddnvr extends Model
{
    protected $table = 'hdd_nvrs';

    protected $fillable = [
        'nvr_id',
        'serial_hdd',
        'capacity_hdd',
        'capacity_max',
        'status'
    ];

    public function nvr()
    {
        return $this->belongsTo(Nvr::class, 'nvr_id', 'mac');
    }
}

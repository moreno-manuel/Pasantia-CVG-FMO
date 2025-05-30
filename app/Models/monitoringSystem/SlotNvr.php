<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Model;

class SlotNvr extends Model
{
    protected $table = 'slot_nvrs';

    protected $fillable = [
        'nvr_id',
        'capacity_max',
        'hdd_serial',
        'hdd_capacity',
        'status'
    ];

    public function nvr()
    {
        return $this->belongsTo(Nvr::class, 'nvr_id', 'mac');
    }
}

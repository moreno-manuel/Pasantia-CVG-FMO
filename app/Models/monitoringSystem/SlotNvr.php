<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    //Relaciones
    public function nvr()
    {
        return $this->belongsTo(Nvr::class);
    }

    //casteos
    protected function nvrId(): Attribute //mac
    {
        return Attribute::make(
            set: fn($nvr_id) => strtoupper($nvr_id),
        );
    }
}

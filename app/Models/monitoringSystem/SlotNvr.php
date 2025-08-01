<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SlotNvr extends Model
{
    use LogsActivity;

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

    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nvr_id',
                'capacity_max',
                'hdd_serial',
                'hdd_capacity',
                'nvr.mac',
                'nvr.name',
            ]);
    }
}

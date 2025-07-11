<?php

namespace App\Models\networkInfrastructure;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CameraInventory extends Model
{
    use LogsActivity;

    protected $table = 'camera_inventories';


    protected $fillable = [
        'mac',
        'mark',
        'model',
        'delivery_note',
        'destination',
        'description'
    ];

    //casteos 

    protected function model(): Attribute //model
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function mac(): Attribute //mac
    {
        return Attribute::make(
            set: fn($mac) => strtoupper($mac),
        );
    }

    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'mac',
                'mark',
                'model',
                'delivery_note',
                'destination',
                'description'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

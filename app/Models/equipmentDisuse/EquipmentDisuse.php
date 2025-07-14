<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;
/* use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions; */

class EquipmentDisuse extends Model
{
    //use LogsActivity; 

    protected $table = 'equipment_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'mark',
        'model',
        'location',
        'description',
        'equipment'
    ];

    //Relaciones

    public function switchDisuse()
    {
        return $this->hasOne(SwitchDisuse::class, 'id', 'id');
    }

    public function linkDisuse()
    {
        return $this->hasOne(LinkDisuse::class, 'id', 'id');
    }

    public function cameraDisuse()
    {
        return $this->hasOne(CameraDisuse::class, 'id', 'id');
    }

    public function nvrDisuse()
    {
        return $this->hasOne(NvrDisuse::class, 'id', 'id');
    }

    public function cameraInventoriesDisuse()
    {
        return $this->hasOne(CameraInventoriesDisuse::class, 'id', 'id');
    }

    //para logs
    /* public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'mark',
                'model',
                'location',
                'description',
                'equipment'
            ]);
    } */
}

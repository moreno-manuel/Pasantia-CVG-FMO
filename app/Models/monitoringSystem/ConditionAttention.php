<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConditionAttention extends Model
{
    use LogsActivity;

    protected $table = 'condition_attentions';

    protected $fillable = [
        'camera_id',
        'name',
        'other_name',
        'date_ini',
        'date_end',
        'description',
        'status',
    ];

    protected $cast = [
        'date_ini' => 'date',
        'date_end' => 'date'
    ];


    //relaciones
    public function camera()
    {
        return $this->belongsTo(Camera::class);
    }

    public function controlCondition()
    {
        return $this->hasMany(ControlCondition::class);
    }

    //casteos 
    protected function otherName(): Attribute //mac
    {
        return Attribute::make(
            set: fn($other_name) => strtoupper($other_name),
        );
    }

    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'camera.name',
                'camera.mac',
                'name',
                'other_name',
                'date_ini',
                'date_end',
                'description',
                'status',
            ]);
    }
}

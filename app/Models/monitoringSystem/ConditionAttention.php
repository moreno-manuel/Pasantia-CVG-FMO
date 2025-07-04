<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Model;

class ConditionAttention extends Model
{
    protected $table = 'condition_attentions';

    protected $fillable = [
        'camera_id',
        'name',
        'date_ini',
        'date_end',
        'description',
        'status',
    ];

    protected $cast = [
        'date_ini' => 'date',
        'date_end' => 'date'
    ];

    public function camera()
    {
        return $this->belongsTo(Camera::class, 'camera_id', 'mac');
    }

    public function controlCondition()
    {
        return $this->hasMany(ControlCondition::class);
    }
}

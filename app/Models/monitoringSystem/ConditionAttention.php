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
        'description'
    ];

    public function camera()
    {
        return $this->belongsTo(Camera::class, 'camera_id', 'mac');
    }
}

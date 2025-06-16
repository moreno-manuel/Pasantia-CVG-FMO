<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Model;

class Descriptions extends Model
{

    protected $table = 'descriptions';

    protected $fillable = [
        'condition_attention_id',
        'text'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    //Relaciones 
    public function conditionAttention()
    {
        return $this->belongsTo(ConditionAttention::class);
    }
}

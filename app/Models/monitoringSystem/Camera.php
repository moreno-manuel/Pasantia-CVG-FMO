<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $table = 'cameras';
    protected $primaryKey = 'mac';
    public $incrementing  = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mac',
        'nvr_id',
        'mark',
        'model',
        'name',
        'location',
        'ip',
        'status',
        'desccription'
    ];

    public function conditionAttention()
    {
        return $this->hasMany(ConditionAttention::class);
    }

    public function nvr()
    {
        return $this->belongsTo(Nvr::class, 'nvr_id', 'mac');
    }
}

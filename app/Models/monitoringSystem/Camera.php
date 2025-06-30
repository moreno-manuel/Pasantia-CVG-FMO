<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'description'
    ];
    //Relaciones 
    public function conditionAttention()
    {
        return $this->hasMany(ConditionAttention::class, 'camera_id', 'mac');
    }

    public function nvr()
    {
        return $this->belongsTo(Nvr::class, 'nvr_id', 'mac');
    }

    //casteos 

    protected function mac(): Attribute //mac
    {
        return Attribute::make(
            set: fn($mac) => strtoupper($mac),
        );
    }

    protected function mark(): Attribute //mark
    {
        return Attribute::make(
            set: fn($mark) => strtoupper($mark),
        );
    }

    protected function model(): Attribute //model
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function name(): Attribute //name
    {
        return Attribute::make(
            set: fn($name) => strtoupper($name),
        );
    }

    protected function location(): Attribute //name
    {
        return Attribute::make(
            set: fn($location) => strtoupper($location),
        );
    }


    // Convertir IP a entero antes de guardar
    public function setIpAttribute($value)
    {
        $this->attributes['ip'] = ip2long($value);
    }

    // Convertir entero a IP al recuperar
    public function getIpAttribute($value)
    {
        return long2ip($value);
    }

    //valor por defecto para status
    protected static function booted()
    {
        static::creating(function ($camera) {
            // Si no tiene status, asignar por defecto
            if (empty($camera->status)) {
                $camera->status = 'online';
            }
        });
    }
}

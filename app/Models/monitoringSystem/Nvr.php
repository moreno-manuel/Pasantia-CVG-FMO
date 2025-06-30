<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Nvr extends Model
{
    protected $table = 'nvrs';
    protected $primaryKey = 'mac';
    public $icrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mac',
        'mark',
        'model',
        'name',
        'ip',
        'ports_number',
        'slot_number',
        'location',
        'status',
        'description'
    ];

    //relaciones
    public function slotNvr()
    {
        return $this->hasMany(SlotNvr::class, 'nvr_id', 'mac');
    }

    public function camera()
    {

        return $this->hasMany(Camera::class, 'nvr_id', 'mac');
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

    //para calcular el nvr hay puertos disponibles 
    public function getAvailablePortsAttribute()
    {
        return $this->ports_number - $this->camera->count();
    }

    //valor por defecto para status
    protected static function booted()
    {
        static::creating(function ($nvr) {
            // Si no tiene status, asignar por defecto
            if (empty($nvr->status)) {
                $nvr->status = 'online';
            }
        });
    }
}

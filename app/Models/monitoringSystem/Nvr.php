<?php

namespace App\Models\monitoringSystem;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Nvr extends Model
{
    use LogsActivity;

    protected $table = 'nvrs';

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
        return $this->hasMany(SlotNvr::class);
    }

    public function camera()
    {

        return $this->hasMany(Camera::class);
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

    //metodos especificos 

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

    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
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
            ])
            ->dontLogIfAttributesChangedOnly(['description']);
    }
}

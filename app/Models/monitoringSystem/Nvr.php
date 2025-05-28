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
        'number_ports',
        'number_hdd',
        'description',
        'status'
    ];

    //relaciones
    public function nvrHdd()
    {
        return $this->hasMany(Hddnvr::class);
    }

    public function camera()
    {

        return $this->hasMany(Camera::class);
    }


    //casteos 

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

    protected function mac(): Attribute
    {
        return Attribute::make(
            set: fn($mac) => strtoupper($mac),
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn($name) => strtoupper($name),
        );
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function mark(): Attribute
    {
        return Attribute::make(
            set: fn($mark) => strtoupper($mark),
        );
    }
}

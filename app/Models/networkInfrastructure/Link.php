<?php

namespace App\Models\networkInfrastructure;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';
    protected $primaryKey = 'mac';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mac',
        'mark',
        'model',
        'name',
        'ssid',
        'location',
        'ip',
        'description'

    ];

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

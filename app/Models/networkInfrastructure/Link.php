<?php

namespace App\Models\networkInfrastructure;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Link extends Model
{
    use LogsActivity;

    protected $table = 'links';

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

    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'mac',
                'mark',
                'model',
                'name',
                'ssid',
                'location',
                'ip',
                'description'
            ]);
    }
}

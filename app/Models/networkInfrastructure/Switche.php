<?php

namespace App\Models\networkInfrastructure;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Switche extends Model
{
    use LogsActivity;


    protected $table = 'switches';


    protected $fillable = [
        'serial',
        'mark',
        'model',
        'location',
        'number_ports',
        'description'
    ];

    //casteos
    protected function serial(): Attribute //serial
    {
        return Attribute::make(
            set: fn($serial) => strtoupper($serial),
        );
    }

    protected function model(): Attribute //model
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function location(): Attribute //location
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
                'serial',
                'mark',
                'model',
                'location',
                'number_ports',
                'description'
            ]);
    }
}

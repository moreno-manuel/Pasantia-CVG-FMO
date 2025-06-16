<?php

namespace App\Models\networkInfrastructure;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Switche extends Model
{
    protected $table = 'switches';
    protected $primaryKey = 'serial';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial',
        'mark',
        'model',
        'location',
        'number_ports',
        'status',
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
}

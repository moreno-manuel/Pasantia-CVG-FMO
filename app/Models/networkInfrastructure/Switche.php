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
        'model',
        'number_ports',
        'user_person',
        'status',
        'description'

    ];

    protected function serial(): Attribute
    {
        return Attribute::make(
            set: fn($serial) => strtoupper($serial),
        );
    }

    protected function model(): Attribute
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }
}

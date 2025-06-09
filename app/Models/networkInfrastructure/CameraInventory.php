<?php

namespace App\Models\networkInfrastructure;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CameraInventory extends Model
{
    protected $table = 'camera_inventories';
    protected $primaryKey = 'mac';
    public $incrementing = false;
    protected $keyType = 'String';

    protected $fillable = [
        'mac',
        'model',
        'mark',
        'delivery_note',
        'description',
        'destination'
    ];

    //casteos 

    protected function model(): Attribute //model
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function mac(): Attribute //mac
    {
        return Attribute::make(
            set: fn($mac) => strtoupper($mac),
        );
    }
}

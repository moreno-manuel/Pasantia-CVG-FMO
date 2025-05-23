<?php

namespace App\Models\networkInfrastructure;

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
}

<?php

namespace App\Models\monitoringSystem;

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
        'description'
    ];

    public function nvrHdd()
    {
        return $this->hasMany(NvrHdd::class);
    }

    public function camera()
    {

        return $this->hasMany(Camera::class);
    }
}

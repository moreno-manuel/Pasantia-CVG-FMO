<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class EquipmentDisuse extends Model
{
    protected $table = 'equipment_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'mark',
        'model',
        'date_delete',
        'descripion'
    ];

    public function switchDisuse()
    {
        return $this->hasOne(SwitchDisuse::class);
    }

    public function linkDisuse()
    {
        return $this->hasOne(LinkDisuse::class);
    }

    public function cameraDisuse()
    {
        return $this->hasOne(CameraDisuse::class);
    }

    public function nvrDisuse()
    {
        return $this->hasOne(NvrDisuse::class);
    }
}

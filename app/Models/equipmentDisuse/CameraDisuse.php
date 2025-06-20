<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class CameraDisuse extends Model
{
    protected $table = 'camera_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'mark',
        'nvr',
        'ip'
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
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
}

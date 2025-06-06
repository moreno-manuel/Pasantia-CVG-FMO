<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class LinkDisuse extends Model
{
    protected $table = 'link_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'ssid',
        'ip'
    ];

    //Relacion

    public function equipmentDisuse()
    {
        return $this->belongsTo(equipmentDisuse::class);
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

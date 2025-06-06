<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class NvrDisuse extends Model
{
    protected $table = 'nvr_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'ip',
        'ports_number',
        'slot_number',
    ];
    //Relaciones
    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }

    public function slotNvrDisuse()
    {
        return $this->hasMany(slotNvrDisuse::class);
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

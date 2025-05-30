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
        'number_port',
        'number_hdd'
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
}

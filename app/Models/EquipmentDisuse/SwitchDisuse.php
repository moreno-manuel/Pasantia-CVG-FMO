<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class SwitchDisuse extends Model
{
    protected $table = 'switch_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable=[
        'id',
        'number_ports',
        'person_user'
    ];

    public function equipmentDisuses(){
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

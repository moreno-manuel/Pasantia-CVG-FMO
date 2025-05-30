<?php

namespace App\Models\equipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class SlotNvrDisuse extends Model
{
    protected $table = 'slot_nvr_disuses';


    protected $fillable = [
        'nvr_id',
        'capacity_max',

    ];
    //Relaciones
    public function nvrDisuse()
    {
        return $this->belongsTo(NvrDisuse::class, 'nvr_id', 'id');
    }
}

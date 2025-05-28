<?php

namespace App\Models\equipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class NvrHddDisuse extends Model
{
    protected $table = 'nvr_hdd_disuses';


    protected $fillable = [
        'nvr_id',
        'capacity_max',

    ];

    public function nvrDisuse()
    {
        return $this->belongsTo(NvrDisuse::class, 'nvr_id', 'id');
    }
}

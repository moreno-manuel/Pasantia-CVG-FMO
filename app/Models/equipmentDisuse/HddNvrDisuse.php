<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class HddNvrDisuse extends Model
{
    protected $table = 'hdd_nvr_disuses';

    protected $fillable = [
        'nvr_id',
        'capcity_max'

    ];

    public function nvrDisuse()
    {
        return $this->belongsTo(NvrDisuse::class);
    }
}

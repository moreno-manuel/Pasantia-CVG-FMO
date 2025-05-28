<?php

namespace App\Models\equipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class Linkdisuse extends Model
{
    protected $table = 'link_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'ssid',
        'location',
        'ip'
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

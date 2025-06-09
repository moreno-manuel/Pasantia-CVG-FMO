<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class CameraInventoriesDisuse extends Model
{
    protected $table = 'camera_inventories_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'mark',
        'destination',
        'delivery_note',
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

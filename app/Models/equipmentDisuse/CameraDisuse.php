<?php

namespace App\Models\equipmentDisuse;

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
        'location',
        'ip'
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

<?php

namespace App\Models\equipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class SwitchDisuse extends Model
{
    protected $table = 'switch_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'number_port',
        'person_user'
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

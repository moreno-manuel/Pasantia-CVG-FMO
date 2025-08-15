<?php

namespace App\Models\EquipmentDisuse;

use Illuminate\Database\Eloquent\Model;

class StockEqDisuse extends Model
{
    protected $table = 'stock_equ_disuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'delivery_note',
    ];

    public function equipmentDisuse()
    {
        return $this->belongsTo(EquipmentDisuse::class);
    }
}

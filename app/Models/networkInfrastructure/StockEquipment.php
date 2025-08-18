<?php

namespace App\Models\networkInfrastructure;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockEquipment extends Model
{
    use LogsActivity;

    protected $table = 'stock_equipments';


    protected $fillable = [
        'mac',
        'equipment',
        'mark',
        'model',
        'delivery_note',
        'description'
    ];

    //casteos 

    protected function model(): Attribute //modelo
    {
        return Attribute::make(
            set: fn($model) => strtoupper($model),
        );
    }

    protected function mac(): Attribute //mac
    {
        return Attribute::make(
            set: fn($mac) => strtoupper($mac),
        );
    }

    protected function mark(): Attribute //marca
    {
        return Attribute::make(
            set: fn($mark) => strtoupper($mark),
        );
    }


    //para logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'mac',
                'mark',
                'model',
                'equipment',
                'delivery_note',
                'description'
            ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'license',
        'name',
        'last_name',
        'sex'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación uno a uno con el modelo User.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    //casteos convertir la primera letra en mayuscula
    protected function name(): Attribute //mac
    {
        return Attribute::make(
            set: fn($name) => ucfirst($name),
        );
    }
    protected function lastName(): Attribute //mac
    {
        return Attribute::make(
            set: fn($last_name) => ucfirst($last_name),
        );
    }
}

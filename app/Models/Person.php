<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Person extends Model
{
    protected $table = 'persons';
    protected $primaryKey = 'license'; // Clave primaria personalizada
    public $incrementing = false; // Desactivar auto-incremento
    protected $keyType = 'string'; // Tipo de la clave primaria

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
        return $this->hasOne(User::class, 'person_id', 'license');
    }
}

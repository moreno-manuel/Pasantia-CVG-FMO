<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la persona
        $person = Person::create([
            'license' => '0',
            'name' => 'Manuel',
            'last_name' => 'Moreno',
            'sex' => 'Masculino',
        ]);

        // Crear el usuario asociado
        User::create([
            'userName' => 'admin',
            'email' => 'manuel@example.com',
            'password' => '0', 
            'rol' => 'admin',
            'person_id' => $person->id,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use App\Models\UserRecoveries;
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
            'name' => 'admin',
            'last_name' => 'admin',
            'sex' => 'Masculino',
        ]);

        // Crear el usuario asociado
        $user = User::create([
            'userName' => 'admin',
            'email' => 'GETE-SecciondeSeguridadTecnologica@ferrominera.com.gob.ve',
            'password' => 'Getesi.ST0',
            'rol' => 'admin',
            'person_id' => $person->id,
        ]);

        UserRecoveries::create([
            'user_id' => $user->id,
            'question_1' => 'Usuario',
            'question_2' => 'Usuario',
            'question_3' => 'Usuario',
            'answer_1' => 'admin',
            'answer_2' => 'admin',
            'answer_3' => 'admin'

        ]);
    }
}

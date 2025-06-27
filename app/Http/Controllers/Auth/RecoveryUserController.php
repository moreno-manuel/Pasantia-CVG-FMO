<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/* controlador para 
recuperacion de usuario */

class RecoveryUserController extends Controller
{
    // Paso 1: Formulario de licencia y usuario
    public function showStep1()
    {
        return view('auth.recoveryStep1');
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'license' => 'required|string',
            'userName' => 'required|string',
        ]);

        $userName = $request->input('userName');

        // Buscar usuario por nombre de usuario
        $user = User::where('userName', $userName)->first();

        if (!$user) {
            // Usuario no existe
            return back()->withErrors(['userName' => 'Usuario no encontrado']);
        } else {
            $license = Person::where('license', $request->input('license'))->first();
            if (!$license)
                // licencia invalida
                return back()->withErrors(['license' => 'Licencia inválida']);
        }


        session(['recovery_user_id' => $user->id]);
        return redirect()->route('recovery.showStep2');
    }

    // Paso 2: Preguntas de seguridad
    public function showStep2()
    {
        $userId = session('recovery_user_id');
        if (!$userId) return redirect()->route('recovery.step1');

        $user = User::findOrFail($userId);

        return view('auth.recoveryStep2', compact('user'));
    }

    public function storeStep2(Request $request)
    {
        $userId = session('recovery_user_id');
        if (!$userId) return redirect()->route('recovery.step1');

        $request->validate([
            "answer_1" => 'required|string',
            "answer_2" => 'required|string',
            "answer_3" => 'required|string'
        ]);

        $user = User::findOrFail($userId);
        $questions = $user->questionsRecovery;

        $error = []; // para mesaje de error
        for ($i = 1; $i < 4; $i++) {
            $answerInput = $request->input("answer_{$i}");
            if ($answerInput != $questions["answer_{$i}"]) {
                $error["answer_{$i}"] = "Respuesta incorrecta";
            }
        }

        if ($error)
            return back()->withErrors($error);

        return redirect()->route('recovery.showStep3');
    }

    // Paso 3: Nueva contraseña
    public function showStep3()
    {
        return view('auth.recoveryStep3');
    }

    public function storeStep3(Request $request)
    {
        $request->validate([
            'password' => '|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
        ], ['regex' => 'La :attribute debe contener al menos una letra y un número']);

        $userId = session('recovery_user_id');
        $user = User::findOrFail($userId);
        $user->update(['password' => $request->password]);

        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('success', 'Contraseña actualizada exitosamente');
    }
}

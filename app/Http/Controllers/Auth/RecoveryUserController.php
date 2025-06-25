<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


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

        $user = User::findOrFail($userId);
        $questions = $user->securityQuestions;

        foreach ($questions as $index => $question) {
            $answerInput = $request->input("answer_{$question->id}");
            if ($answerInput !== $question->answer) {
                return back()->withErrors(['error' => 'Respuesta(s) incorrecta(s)']);
            }
        }

        return redirect()->route('recovery.step3');
    }

    // Paso 3: Nueva contraseña
    public function showStep3()
    {
        return view('recovery.step3');
    }

    public function storeStep3(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $userId = session('recovery_user_id');
        $user = User::findOrFail($userId);
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('status', 'Contraseña actualizada exitosamente');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validación básica (opcional, puedes dejarlo o quitarlo según necesites)
        $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $userName = $request->input('userName');
        $password = $request->input('password');

        // Buscar usuario por nombre de usuario
        $user = User::where('userName', $userName)->first();

        if (!$user) {
            // Usuario no existe
            return back()->withErrors(['userName' => 'Usuario no encontrado']);
        }

        // Verificar contraseña
        if (!Auth::attempt(['userName' => $userName, 'password' => $password], $request->filled('remember'))) {
            // Contraseña incorrecta
            return back()->withErrors(['password' => 'Contraseña incorrecta']);
        }

        // Login exitoso
        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Illuminate\Http\Request;

/* controlador para 
actulizar datos del perfil 
de usuario */

class PerfilController extends Controller
{
    //

    public function edit($user)
    {
        $user = User::where('userName', $user)->first();

        return view('front.perfil.edit', compact('user'));
    }

    public function update(Request $request) {}
}

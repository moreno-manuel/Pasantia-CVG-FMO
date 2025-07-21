<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/* controlador para 
actulizar datos del perfil 
de usuario logueado*/

class PerfilController extends Controller
{
    //

    public function edit($user)
    {
        $user = User::where('userName', $user)->first();
        return view('front.perfil.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|alpha',
                'last_name' => 'required|min:3|regex:/^[a-zA-Z ]+$/',
                'sex' =>  'required',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user, 'userName'),
                ],
                'password' => 'nullable|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                'password_confirmation' => 'required_with:password'
            ],
            ['regex' => 'La :attribute debe contener al menos una letra y un número'],
            ['name' => 'Nombre', 'last_name' => 'Apellido']
        );

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);


        $user = User::where('userName', $user)->first();
        if ($request->filled('password'))
            $user->update([
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
        else
            $user->update([
                'email' => $request->input('email'),
            ]);


        $person = $user->person;
        $person->update([
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex')
        ]);


        return redirect()->back()->with('success', 'Datos Actualizados exitosamente');
    }
}

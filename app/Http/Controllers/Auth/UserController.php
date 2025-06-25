<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/* controlador para el crud
de personas (usuarios) */

class UserController extends Controller
{
    //

    public function index()
    {
        $persons = Person::orderBy('created_at', 'desc')->paginate(10);
        return view('front.user.index', compact('persons'));
    }

    public function create()
    {
        $roles = json_decode(file_get_contents(resource_path('js/data.json')), true)['rol']; // json con las marcas agregadas
        return view('front.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|alpha',
            'last_name' => 'required|min:3|alpha',
            'sex' => 'required',
            'license' => 'required|alpha_num|unique:persons,license|min:3',
            'userName' => [
                'required',
                'unique:users,userName',
                'min:6',
                'max:12',
                'alpha_num', // Letras, números y puntos (al menos una letra)
            ],
            'rol' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            'password_confirmation' => 'required_with:password'
        ], [], [
            'userName' => 'Nombre de usuario',
            'license' => 'Ficha',
            'name' => 'Nombre',
            'last_name' => 'Apellido'
        ]);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);

        $person = Person::create([
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex'),
            'license' => $request->input('license')
        ]);

        User::create([
            'person_id' => $person->license,
            'userName' => $request->input('userName'),
            'email' => $request->input('email'),
            'rol' => $request->input('rol'),
            'password' => $request->input('password')

        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit($userName)
    {
        $user = User::where('userName', $userName)->first();

        $roles = json_decode(file_get_contents(resource_path('js/data.json')), true)['rol']; // json con las marcas agregadas
        return view('front.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $person)
    {
        $person = Person::findorFail($person);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|alpha',
            'last_name' => 'required|min:3|alpha',
            'sex' => 'required',
            'userName' =>
            [
                'required',
                'min:6',
                'max:12',
                'alpha_num', // Letras, números y puntos (al menos una letra)
                Rule::unique('users')->ignore($person->user->userName, 'userName')
            ],
            'rol' => 'required',
            'email' =>
            [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($person->user->email, 'email')
            ],
            'password' => 'nullable|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            'password_confirmation' => 'required_with:password'
        ], [], [
            'userName' => 'Nombre de usuario',
            'name' => 'Nombre',
            'last_name' => 'Apellido'
        ]);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);


        $person->update([
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex')
        ]);

        if ($request->filled('password'))
            $person->user->update([
                'userName' => $request->input('userName'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
        else
            $person->user->update([
                'userName' => $request->input('userName'),
                'rol' => $request->input('rol'),
                'email' => $request->input('email')
            ]);



        return redirect()->route('users.index')->with('success', 'Datos de usuario actualizados exitosamente ');
    }

    public function destroy($person)
    {

        $person = Person::findOrFail($person);

        if ($person->is(Auth::user()->person))
            return redirect()->route('users.index')->with('warning', 'No se puede eliminar el usuario logueado');

        $person->delete();
        return redirect()->route('users.index')->with('success', 'Usuario Eliminado exitosamente.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
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
            'name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'license' => 'required|unique:persons,license',
            'userName' => 'required|unique:users,userName',
            'rol' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required_with:password'
        ], [
            'license' => 'El campo ficha ya ha sido registrado',
            'userName' => 'El campo nombre de usuario ya ha sido registrado',
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

        return redirect()->route('users.index')->with('success', 'Usuario creardo exitosamente');
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
            'name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'userName' =>
            [
                'required',
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
            'password' => 'nullable|confirmed|min:8',
            'password_confirmation' => 'required_with:password'
        ], [
            'license' => 'El campo ficha ya ha sido registrado',
            'userName' => 'El campo nombre de usuario ya ha sido registrado',
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
                'email' => $request->input('email')
            ]);



        return redirect()->route('users.index')->with('success', 'Datos de usuario actualizados exitosamente ');
    }

    public function show($userName)
    {
        $user = User::where('userName', $userName)->first();

        $person = $user->person;
        return view('front.user.show', compact('person'));
    }

    public function destroy($person)
    {

        $person = Person::findOrFail($person);
        $person->delete();

        return redirect()->route('users.index')->with('success', 'Usuario Eliminado exitosamente');
    }
}

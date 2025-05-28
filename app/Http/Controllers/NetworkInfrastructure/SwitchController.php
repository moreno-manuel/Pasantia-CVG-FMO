<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\networkInfrastructure\Switche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;

//controlador para el crud del switch
class SwitchController extends Controller
{
    public function index(Request $request) //muestra los registros en la tabla principal
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('serial') ||
            $request->filled('model') ||
            $request->filled('number_ports');

        if (!$hasFilters) { //si no se aplica un filtro
            $switches = Switche::paginate(5);
            return view('front.switch.index', compact('switches'));
        }

        return filter($request,'switches'); //helper
    }

    public function create() //muestra la vista para crear un nuevo switch
    {
        return view('front.switch.create');
    }

    public function store(Request $request) //guarda los datos de un switch nuevo
    {

        $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
            'serial' => 'required|unique:switches',
            'model' => 'required',
            'number_ports' => 'required',
            'user_person' => 'required',
            'status' => 'required',
            'description' => 'nullable'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $switch = Switche::create($request->all());
        $switch->save();
        Session::flash('success', 'Switch Agregado Exitosamente');
        return redirect()->route('switch.index');
    }

    public function destroy(Switche $switch) //Elimina un switch
    {
        $switch->delete();
        Session::flash('success', 'Switch Eliminado Exitosamente');
        return redirect()->route('switch.index');
    }

    public function show(Switche $switch) //muestra la vista y datos para los detalles un switch
    {
        return view('front.switch.show', compact('switch'));
    }

    public function edit(Switche $switch) //muestra la vista para editar un switch
    {
        return view('front.switch.edit', compact('switch'));
    }

    public function update(Request $request, Switche $switch) //Actualiza los datos de un switch
    {

        $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
            'number_ports' => 'required',
            'user_person' => 'required',
            'status' => 'required',
            'description' => 'nullable'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $switch->update($request->all());
        return redirect()->route('switch.show', $switch);
    }
}

<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\networkInfrastructure\Switche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;

//controlador para el crud del switch
class SwitchController extends Controller
{

    public function index(Request $request) //muestra los registros en la tabla principal switch
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('serial') ||
            $request->filled('location') ||
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $switches = Switche::orderBy('created_at', 'desc')->paginate(10);
            return view('front.switch.index', compact('switches'));
        }

        return filter($request, 'switches'); //helper
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
            'location' => 'required',
            'number_ports' => 'required',
            'status' => 'required',
            'description' => 'nullable'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Switche::create($request->all())->save();

        return redirect()->route('switch.index')->with('success', 'Switch creado exitosamente.');
    }

    public function destroy(Switche $switch) //Elimina un switch
    {
        $switch->delete();
        return redirect()->route('switch.index')->with('success', 'Switch Eliminado exitosamente.');
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
            'location' => 'required',
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

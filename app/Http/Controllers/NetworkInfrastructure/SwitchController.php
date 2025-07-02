<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\SwitchDisuse;
use App\Models\networkInfrastructure\Switche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;
use function app\Helpers\marksUpdate;

//controlador para el crud del switch
class SwitchController extends Controller
{

    public function index(Request $request) //muestra los registros en la tabla principal switch
    {

        $hasFilters = $request->filled('serial') || // Valida si hay algún filtro activo
            $request->filled('location');

        if (!$hasFilters) { //si no se aplica un filtro
            $switches = Switche::orderBy('created_at', 'desc')->paginate(10);
            return view('front.switch.index', compact('switches'));
        }

        return filter($request, 'switches'); //helper
    }

    public function create() //muestra la vista para crear un nuevo switch
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['switch_marks']; // json con las marcas agregadas
        return view('front.switch.create', compact('marks'));
    }

    public function store(Request $request) //guarda los datos de un switch nuevo
    {

        $validator = Validator::make(
            $request->all(),
            [
                'serial' => 'required|unique:switches|alpha_num|min:9|max:10',
                'mark' => 'required',
                'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                'location' => 'required|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5',
                'model' => 'required|alpha_dash|min:3',
                'number_ports' => 'required',
                'description' => 'nullable'
            ],
            ['required_if' => 'Debe agregar el nombre de la marca'],
            ['serial' => 'Serial', 'location' => 'Localidad', 'model' => 'Modelo']
        );


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = marksUpdate($request, 'switch_marks');

        Switche::create($request->all())->save();
        return redirect()->route('switch.index')->with('success', 'Switch creado exitosamente.');
    }

    public function edit(Switche $switch) //muestra la vista para editar un switch
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['switch_marks']; // json con las marcas agregadas
        return view('front.switch.edit', compact('switch', 'marks'));
    }

    public function update(Request $request, Switche $switch) //Actualiza los datos de un switch
    {

        $validator = Validator::make(
            $request->all(),
            [ //para capturar si hay dato incorrecto
                'mark' => 'required',
                'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                'number_ports' => 'required',
                'location' => 'required|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5',
                'model' => 'required|alpha_dash|min:3',
                'description' => 'nullable'
            ],
            ['required_if' => 'Debe agregar el nombre de la marca'],
            ['serial' => 'Serial', 'location' => 'Localidad', 'model' => 'Modelo']
        );


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $switch->update($request->all());
        return redirect()->route('switch.index')->with('success', 'Switch actualizado exitosamente.');
    }

    public function show(Switche $switch) //muestra la vista y datos para los detalles un switch
    {
        return view('front.switch.show', compact('switch'));
    }

    public function destroy(Switche $switch, Request $request) //Elimina un switch
    {
        $equipment = EquipmentDisuse::find($switch->serial);

        if ($equipment)
            return redirect()->route('switch.index')->with('warning', 'Ya existe un registro eliminado con el mismo ID.');

        EquipmentDisuse::create([
            'id' => $switch->serial,
            'mark' => $switch->mark,
            'model' => $switch->model,
            'location' => $switch->location,
            'equipment' => 'Switch',
            'description' => $request->input('deletion_description')
        ]);

        SwitchDisuse::create([
            'id' => $switch->serial,
            'number_ports' => $switch->number_ports
        ]);

        $switch->delete();
        return redirect()->route('switch.index')->with('success', 'Switch eliminado exitosamente.');
    }
}

<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\SwitchDisuse;
use App\Models\networkInfrastructure\Switche;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;
use function app\Helpers\locationUpdate;
use function app\Helpers\marksUpdate;

/* controlador para el 
crud del switch */

class SwitchController extends Controller
{

    public function index(Request $request)
    {

        $hasFilters = $request->filled('model') ||
            $request->filled('location');

        if (!$hasFilters) { //si no se aplica un filtro
            $switches = Switche::select('serial', 'mark', 'model', 'number_ports', 'location')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
            return view('front.switch.index', compact('switches', 'locations'));
        }

        return filter($request, 'switches'); //helper
    }

    public function create()
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['switch_marks']; // json con las marcas 
        $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades
        return view('front.switch.create', compact('marks', 'locations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'serial' => 'required|unique:switches|alpha_num|min:9|max:10',
                'mark' => 'required',
                'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                'location' => 'required',
                'other_location' => 'nullable|alpha_num|min:3|required_if:location,Otra',
                'model' => 'required|alpha_dash|min:3',
                'number_ports' => 'required',
                'description' => 'nullable'
            ],
            ['required_if' => 'Debe agregar el nombre de :attribute'],
            ['serial' => 'Serial', 'location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca', 'other_location' => 'Localidad']
        );


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = marksUpdate($request, 'switch_marks'); // en caso de que haya una marca nueva
        $request = locationUpdate($request, 'locations'); // en caso de que haya una localidad nueva

        Switche::create($request->all())->save();
        return redirect()->route('switch.index')->with('success', 'Switch creado exitosamente.');
    }

    public function edit($serial)
    {
        try {
            $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
            if ($redirectRoute != 'switch.edit')
                session(['switchUrl' => url()->previous()]);

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['switch_marks']; // json con las marcas agregadas
            $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades
            
            $switch = Switche::where('serial', $serial)->firstOrFail();
            return view('front.switch.edit', compact('switch', 'marks', 'locations'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('enlace.index')->with('warnings', 'Switch no encontrado');
        }
    }

    public function update(Request $request, $serial)
    {
        $switch = Switche::where('serial', $serial)->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'mark' => 'required',
                'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                'number_ports' => 'required',
                'location' => 'required',
                'other_location' => 'nullable|alpha_num|min:3|required_if:location,Otra',
                'model' => 'required|alpha_dash|min:3',
                'description' => 'nullable'
            ],
            ['required_if' => 'Debe agregar el nombre de :attribute'],
            ['serial' => 'Serial', 'location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca', 'other_location' => 'Localidad']
        );


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = marksUpdate($request, 'switch_marks'); // en caso de que haya una marca nueva
        $request = locationUpdate($request, 'locations'); // en caso de que haya una localidad nueva

        $switch->update($request->all());

        $redirectRoute = Route::getRoutes()->match(app('request')->create(session('switchUrl')))->getName();
        if ($redirectRoute === 'switch.show')
            return redirect()->route('switch.show', ['switch' => $switch->serial])->with('success', 'Switch actualizado.');

        return redirect()->route('switch.index')->with('success', 'Switch actualizado.');
    }

    public function show($serial)
    {
        $switch = Switche::where('serial', $serial)->firstOrFail();
        return view('front.switch.show', compact('switch'));
    }

    public function destroy($serial, Request $request)
    {
        $switch = Switche::where('serial', $serial)->firstOrFail();


        $equipment = EquipmentDisuse::find($serial);
        if ($equipment) //si en el historial de equipos eliminados existe el mismo serial
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

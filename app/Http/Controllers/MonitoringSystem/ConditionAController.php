<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\ControlCondition;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function app\Helpers\filter;

/* Controlador crud 
condición de atención */

class ConditionAController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->filled('name')) { //si no se aplica un filtro
            $conditions = ConditionAttention::where('status', 'por atender')
                ->orderBy('id', 'desc')
                ->paginate(10);

            $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json con los tipos de condicion

            return view('front.attention.index', compact('conditions', 'names'));
        }
        return filter($request, 'conditions'); //helper
    }

    public function create()
    {
        $nvrs = Nvr::with('camera')->select('id', 'name')->get();

        $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json con los tipos de condicion
        return view('front.attention.create', compact('nvrs', 'names'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'other_condition' => 'nullable|regex:/^[a-zA-Z\s]+$/u|min:5|required_if:name,OTRO',
            'camera_id' => 'required',
            'date_ini' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_ini',
            'description' => 'required'
        ], [
            'after_or_equal' => 'La :attribute debe ser posterior o igual a :date',
            'required_if' => 'Formato de :attribute inválido'
        ], [
            'date_ini' => 'Fecha de Inicio',
            'date_end' => 'Fecha de Realización',
            'other_condition' => 'Nombre'
        ]);

        $checkName = false; // verifica si nuevo nombre esta en el arreglo
        $conditionExists = false; // verifica que no haya una condicion nombre y fecha igual 
        if ($request->filled('other_condition')) {
            $conditionExists = ConditionAttention::where('other_name', $request->input('other_condition'))
                ->where('date_ini', $request->input('date_ini'))
                ->exists(); // consulta si existe una condicion con el mismo nombre y fecha 

            $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json
            $checkName = in_array(strtoupper($request->input('other_condition')), $names); //nueva condicion no este en arreglo
        } else
            $conditionExists = ConditionAttention::where('name', $request->input('name'))
                ->where('date_ini', $request->input('date_ini'))
                ->exists(); // consulta si existe una condicion con el mismo nombre y fecha 



        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else if ($conditionExists) { //si existe retorna un error
            $validator->errors()->add(
                $request->input('name') === 'OTRO' ? 'other_condition' : 'name',
                'Ya existe una condición con este nombre y fecha de inicio para la cámara ' . Camera::where('id', $request->input('camera_id'))->value('name')
            );
            return redirect()->back()->withInput()->withErrors($validator);
        } else if ($checkName) { //si nuevo nombre ya se encontraba en la lista
            $validator->errors()->add('other_condition', 'El nombre de la condición ya está en la lista');
            return redirect()->back()->withInput()->withErrors($validator);
        }


        ConditionAttention::create([
            'name' => $request->input('name'),
            'other_name' => $request->filled('other_condition') ? strtoupper($request->input('other_condition')) : null,
            'camera_id' => $request->input('camera_id'),
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $request->filled('date_end') ? 'Atendido' : 'Por atender' // Determinar el status de la condicion
        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención agregada exitosamente.');
    }

    public function edit($id)
    {
        $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
        if ($redirectRoute != 'atencion.edit')
            session(['url' => url()->previous()]); //captura ruta desde donde se llama el metodo

        $condition = ConditionAttention::findOrFail($id);
        return view('front.attention.edit', compact('condition'));
    }

    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'date_end' => 'nullable|date',
            'description' => [
                Rule::when(
                    $request->filled('date_end'), // Si se proporcionó una fecha
                    'sometimes',                   // Entonces es opcional
                    'required'                   // Si no, es requerido
                ),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $condition = ConditionAttention::findOrFail($id);
        if ($request->filled('description')) { //si se agrega una descripción
            ControlCondition::create([
                'condition_attention_id' => $condition->id,
                'text' => $request->input('description'),
            ]);
        }

        if ($request->filled('date_end'))
            $condition->update([
                'date_end' => $request->input('date_end'),
                'status' => $request->filled('date_end') ? 'Atendido' : 'Por atender'
            ]);

        $redirectRoute = Route::getRoutes()->match(app('request')->create(session('url')))->getName();
        if ($redirectRoute != 'atencion.show')
            return redirect(session('url'))->with('success', 'Condición de atención actualizada.');

        return redirect()->route('atencion.show', ['atencion' => $condition->id])->with('success', 'Condición de atención actualizada.');
    }

    public function show($id)
    {
        try {
            $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
            if ($redirectRoute != 'atencion.edit')
                session(['urlAtencion' => url()->previous()]);

            $condition = ConditionAttention::findOrFail($id);
            $controlConditions = $condition->controlCondition()->orderBy('created_at', 'desc')->paginate(5); //obtiene las descripciones de la condición
            return view('front.attention.show', compact('condition', 'controlConditions'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('atencion.index')->with('warnings', 'Condición de atención no encontrada');
        }
    }

    public function destroy($id)
    {
        $condition = ConditionAttention::findOrFail($id);
        $condition->delete();

        $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
        if ($redirectRoute === 'atencion.index')
            return back()->with('success', 'Condición de atención eliminada exitosamente.');
        else if (session()->has('urlAtencion'))
            return redirect(session('urlAtencion'))->with('success', 'Condición de atención eliminada exitosamente.');
        else
            return redirect(url()->previous())->with('success', 'Condición de atención eliminada exitosamente.');
    }
}

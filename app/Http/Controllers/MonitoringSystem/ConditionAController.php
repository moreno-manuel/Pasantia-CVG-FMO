<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\ControlCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function app\Helpers\conditionValidate;
use function app\Helpers\filter;

/* Controlador crud 
condición de atención */

class ConditionAController extends Controller
{
    public function index(Request $request) ///muestra todo los registros
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
        $cameras = Camera::where('status', 'offline')->select('name', 'mac')->get();

        if (!$cameras)
            return redirect()->back()->with('warning', 'No hay Cámaras fuera de servicio');

        $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json con los tipos de condicion
        return view('front.attention.create', compact('cameras', 'names'));
    }

    public function store(Request $request) //guarda el registro nuevo
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'other_condition' => 'nullable|regex:/^[a-zA-Z\s]+$/u|min:5|required_if:name,OTROS',
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


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $condition = ConditionAttention::where('camera_id', $request->input('camera_id'))
            ->latest()
            ->first(); //busqueda explicita,last condición
        if ($condition) {
            if ($request->filled('other_condition'))
                $request['name'] = strtoupper($request->input('other_condition'));
            $validate = conditionValidate($request, $condition); //reglas de validación 
            if ($validate != 'success')
                return redirect()->back()->withInput()->withErrors($validate); //retorna el tipo de error 
        }

        $status = $request->filled('date_end') ? 'Atendido' : 'Por atender'; // Determinar el status de la condicion

        $condition = ConditionAttention::create([
            'name' => $request->input('name'),
            'camera_id' => $request->input('camera_id'),
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $status

        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención agregada exitosamente.');
    }


    public function edit($id) //vista para editar un registro
    {
        $condition = ConditionAttention::findOrFail($id);
        return view('front.attention.edit', compact('condition'));
    }


    public function update(Request $request,  $id) //actualiza los datos de un registro
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

        $status = $request->filled('date_end') ? 'Atendido' : 'Por atender';
        $condition->update([
            'date_end' => $request->input('date_end'),
            'status' => $status,

        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención actualizada exitosamente.');
    }


    public function show($id) //muestra los detalles de un registro
    {
        $condition = ConditionAttention::findOrFail($id);
        $controlConditions = $condition->controlCondition()->orderBy('created_at', 'desc')->paginate(5); //obtiene las descripciones de la condición
        return view('front.attention.show', compact('condition', 'controlConditions'));
    }


    public function destroy($id) //Elimina un regitro 
    {
        $condition = ConditionAttention::findOrFail($id);

        $condition->delete();
        return redirect()->route('atencion.index')->with('success', 'Condición de atención eliminada exitosamente.');
    }
}

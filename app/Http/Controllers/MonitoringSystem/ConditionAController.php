<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\ControlCondition;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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


        if ($validator->fails()) {
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

        return redirect()->route('atencion.show', ['atencion' => $condition->id])->with('success', 'Condición de atención actualizada.');
    }

    public function show($id)
    {
        try {
            session(['url' => url()->previous()]); //captura ruta desde donde se llama el metodo

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

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'atencion/'))
            return redirect(session('url'))->with('success', 'Condición de atención eliminada exitosamente.');

        return back()->with('success', 'Condición de atención eliminada exitosamente.');
    }
}

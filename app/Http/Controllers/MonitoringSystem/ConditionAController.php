<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;

/* Controlador crud 
condición de atención */

class ConditionAController extends Controller
{
    public function index(Request $request) ///muestra todo los registros
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('name');

        if (!$hasFilters) { //si no se aplica un filtro
            $conditions = ConditionAttention::orderBy('id', 'desc')->paginate(10);
            return view('front.attention.index', compact('conditions'));
        }

        return filter($request, 'conditions'); //helper
    }

    public function create()
    {
        $cameras = Camera::all();
        return view('front.attention.create', compact('cameras'));
    }

    public function store(Request $request) //guarda el registro nuevo
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'camera_id' => 'required',
            'date_ini' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_ini',
            'description' => 'nullable'
        ], [
            'after_or_equal' => 'La :attribute debe ser posterior o igual a :date'
        ], [
            'date_ini' => 'Fecha de Inicio',
            'date_end' => 'Fecha de Realización'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            //busqueda filtrada [nombre - fecha incial - camara] 
            $existingCondition = ConditionAttention::where('camera_id', $request->input('camera_id'))
                ->where('date_ini', $request->input('date_ini'))
                ->where('name', $request->input('name'))
                ->first();
            if ($existingCondition) //valida que no haya una condicion de atencion con el mismo nombre y fecha de inicio
                return redirect()->back()->withInput()->withErrors('Ya existe una condición de atención con la misma fecha y tipo para la cámara seleccionada');
            else { //valida que no haya una condición sin cerrar
                $existingCondition = ConditionAttention::where('camera_id', $request->input('camera_id'))
                    ->whereNull('date_end')->first();
                if ($existingCondition)
                    return redirect()->back()->withInput()->withErrors('Debe finalizar la condición de atención anterior para la cámara selccionada');
            }
        }

        // Determinar el estado
        $status = $request->filled('date_end') ? 'Atendido' : 'Por atender';

        ConditionAttention::create([
            'name' => $request->input('name'),
            'camera_id' => $request->input('camera_id'),
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $status,

        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención agregada exitosamente');
    }

    public function destroy($id) //Elimina un regitro 
    {
        // Recupera el modelo manualmente
        $condition = ConditionAttention::where('id', $id)->first();
        $condition->delete();
        return redirect()->route('atencion.index')->with('success', 'Conición de atención eliminado exitosamente.');
    }

    public function show($id) //muestra los detalles de un registro
    {
        $condition = ConditionAttention::where('id', $id)->first();
        return view('front.attention.show', compact('condition'));
    }

    public function edit($id) //vista para editar un registro
    {
        $condition = ConditionAttention::findOrFail($id);
        // Excluir la cámara asociada
        $cameras = Camera::where('mac', '!=', $condition->camera_id)->get();

        return view('front.attention.edit', compact('condition', 'cameras'));
    }

    public function update(Request $request,  $id) //actualiza los datos de un registro
    {
        $condition = ConditionAttention::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'camera_id' => 'required',
            'date_ini' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_ini',
            'description' => 'nullable'
        ], [
            'after_or_equal' => 'La :attribute debe ser posterior o igual a :date'
        ], [
            'date_ini' => 'Fecha de Inicio',
            'date_end' => 'Fecha de Realización'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Determinar el estado
        $status = $request->filled('date_end') ? 'Atendido' : 'por atender';

        $condition->update([
            'camera_id' => $request->input('camera_id'),
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $status,

        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención actualizada exitosamente');
    }
}

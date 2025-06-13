<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\conditionValidate;
use function app\Helpers\conditionValidateUpdate;
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
        $cameras = Camera::all();

        $names = json_decode(file_get_contents(resource_path('js/data.json')), true)['conditions']; // json con los tipos de condicion

        return view('front.attention.create', compact('cameras', 'names'));
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
        }

        $condition = ConditionAttention::where('camera_id', $request->input('camera_id'))->latest()->first(); //busqueda explicita,last condición

        if ($condition) {
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
            'status' => $status,

        ]);

        //atualiza el estado de la cámara
        if ($status == 'Por atender')
            $condition->camera->update(['status' => 'Inactivo']);


        return redirect()->route('atencion.index')->with('success', 'Condición de Atención agregada exitosamente');
    }


    public function edit($id) //vista para editar un registro
    {
        $condition = ConditionAttention::find($id);
        return view('front.attention.edit', compact('condition'));
    }


    public function update(Request $request,  $id) //actualiza los datos de un registro
    {
        $conditionUpdate = ConditionAttention::find($id);

        $validator = Validator::make($request->all(), [
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

        $condition = ConditionAttention::where('camera_id', $request->input('camera_id'))->latest()->first(); //busqueda explicita,last condición

        if ($condition) {
            $validate = conditionValidateUpdate($request, $condition); //reglas de validación 
            if ($validate != 'success')
                return redirect()->back()->withInput()->withErrors($validate); //retorna el tipo de error 
        }

        $status = $request->filled('date_end') ? 'Atendido' : 'Por atender';

        $conditionUpdate->update([
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $status,

        ]);

        //atualiza el estado de la cámara
        if ($status == 'Atendido')
            $conditionUpdate->camera->update(['status' => 'Activo']);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención actualizada exitosamente');
    }


    public function show($id) //muestra los detalles de un registro
    {
        $condition = ConditionAttention::find($id);
        return view('front.attention.show', compact('condition'));
    }


    public function destroy($id) //Elimina un regitro 
    {
        $condition = ConditionAttention::find($id);

        $conditionLast = ConditionAttention::where('camera_id', $condition->camera_id)->latest()->first(); //busqueda explicita,last condición

        if ($conditionLast) {
            if ($condition->is($conditionLast)) { //si la que se elimina es la ultima condición 
                $condition->camera->update(['status' => 'Activo']); // cámara regresa a su status original
            }
        }

        $condition->delete();
        return redirect()->route('atencion.index')->with('success', 'Conición de atención eliminado exitosamente.');
    }
}

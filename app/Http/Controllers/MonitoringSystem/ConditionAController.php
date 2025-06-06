<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use Carbon\Carbon;
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
            //busqueda explicita
            $condition = ConditionAttention::where('camera_id', $request->input('camera_id'))->latest()->first();
            if ($condition) {
                //para validar fecha futura
                $date_max = Carbon::parse($request->input('date_ini'))->isFuture();

                //si ya existe una atencion generada
                if ((($condition->name == $request->input('name')) > 0) && (($condition->date_ini == $request->input('date_ini')) > 0)) {
                    return redirect()->back()->withInput()->withErrors('Ya existe una condición de atención con el mismo tipo y fecha para la cámara seleccionada');

                    //si no se ha culminado la ultima atención
                } else if (!$condition->date_end) {
                    return redirect()->back()->withInput()->withErrors('Existe una condición de atención sin finalizar para la cámara seleccionada');

                    //si la fecha final de la ultima atencion supera a la fecha inicial de la nueva atencion
                } else if ($condition->date_end > $request->input('date_ini')) {
                    return redirect()->back()->withInput()->withErrors('La nueva condición de atención para la cámara seleccionada debe tener una fecha mayor o igual a la anterior (' . $condition->date_end . ")");

                    //si se ingresa fechas futuras
                } else if ($date_max) {
                    return redirect()->back()->withInput()->withErrors('La fecha ingresada supera la fecha actual (' . Carbon::now()->format('d/m/Y') . ')');
                }
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
        return view('front.attention.edit', compact('condition'));
    }

    public function update(Request $request,  $id) //actualiza los datos de un registro
    {
        $condition = ConditionAttention::findOrFail($id);

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
        } else {
            //busqueda explicita
            $conditionValidate = ConditionAttention::where('camera_id', $request->input('camera_id'))->latest()->first();

            if ($conditionValidate) {
                //para validar fecha futura
                $date_max = Carbon::parse($request->input('date_ini'))->isFuture();

                //si la fecha final de la ultima atencion supera a la fecha inicial de la nueva atencion
                if ($conditionValidate->date_end > $request->input('date_ini')) {
                    return redirect()->back()->withInput()->withErrors('La nueva condición de atención para la cámara seleccionada debe tener una fecha mayor o igual a la anterior (' . $condition->date_end . ")");

                    //que no se ingreses fechas futuras
                } else if ($date_max) {
                    return redirect()->back()->withInput()->withErrors('La fecha ingresada supera la fecha actual (' . Carbon::now()->format('d/m/Y') . ')');
                }
            }
        }

        // Determinar el estado
        $status = $request->filled('date_end') ? 'Atendido' : 'por atender';

        $condition->update([
            'date_ini' => $request->input('date_ini'),
            'date_end' => $request->input('date_end'),
            'description' => $request->input('description'),
            'status' => $status,

        ]);

        return redirect()->route('atencion.index')->with('success', 'Condición de Atención actualizada exitosamente');
    }
}

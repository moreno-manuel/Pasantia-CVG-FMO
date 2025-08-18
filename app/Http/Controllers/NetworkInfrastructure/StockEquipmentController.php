<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\StockEqDisuse;
use App\Models\networkInfrastructure\StockEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\equipmentUpdate;
use function app\Helpers\filter;
use function app\Helpers\marksUpdate;

/* controlador para el 
crud de camaras en inventario */

class StockEquipmentController extends Controller
{

    public function index(Request $request)
    {
        $hasFilters = $request->filled('equipment') || $request->filled('model') ||
            $request->filled('delivery_note');

        if (!$hasFilters) { // Valida si hay algún filtro activo
            $eqs = StockEquipment::select('mac', 'equipment', 'mark', 'model', 'delivery_note', 'description')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas

            $equipments = json_decode(file_get_contents(resource_path('js/data.json')), true)['equipments']; // json tipo de quipos

            return view('front.stock.index', compact('eqs', 'marks', 'equipments'));
        }

        return filter($request, 'stock');
    }

    public function create()
    {
        $equipments = json_decode(file_get_contents(resource_path('js/data.json')), true)['equipments']; // json tipo de quipos

        return view('front.stock.create', compact('equipments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mac' => 'required|unique:stock_equipments|alpha_num|size:12',
                'model' => 'required|alpha_num|min:3',
                'mark' => 'nullable|alpha_num|min:3',
                'delivery_note' => 'required|numeric|min:3',
                'equipment' => 'required',
                'other_eq' => 'nullable|regex:/^[a-zA-Z0-9\/\-.Ññ ]+$/|min:3|required_if:equipment,Otro',
                'description' => 'required'

            ],
            ['required_if' => 'Debe agregar el tipo de :attribute'],
            ['model' => 'Modelo', 'delivery_note' => 'Nota de entrega', 'mark' => 'Marca', 'equipment' => 'Equipo', 'other_eq' => 'Equipo']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $request = equipmentUpdate($request, 'equipments'); //verifica si hay un equipo nuevo


        StockEquipment::create($request->all())->save();
        return redirect()->route('stock.index')->with('success', 'Equipo agregada exitosamente');
    }

    public function edit($mac)
    {
        $eq = StockEquipment::where('mac', $mac)->firstOrFail(); //verifica si existe el equipo

        $equipments = json_decode(file_get_contents(resource_path('js/data.json')), true)['equipments']; // json tipo de quipos

        return view('front.stock.edit', compact('equipments', 'eq'));
    }

    public function update($mac, Request $request)
    {
        $eq = StockEquipment::where('mac', $mac)->firstOrFail(); //verifica si existe el equipo

        $validator = Validator::make(
            $request->all(),
            [
                'model' => 'required|alpha_num|min:3',
                'mark' => 'nullable|alpha_num|min:3',
                'delivery_note' => 'required|numeric|min:3',
                'equipment' => 'required',
                'other_eq' => 'nullable|regex:/^[a-zA-Z0-9\/\-.Ññ ]+$/|min:3|required_if:equipment,Otro',
                'description' => 'required'

            ],
            ['required_if' => 'Debe agregar :attribute'],
            ['model' => 'Modelo', 'delivery_note' => 'Nota de entrega', 'mark' => 'Marca', 'equipment' => 'Equipo', 'other_eq' => 'Equipo']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = equipmentUpdate($request, 'equipments'); //verifica si hay un equipo nuevo

        $eq->update($request->all());

        return redirect()->route('stock.index')->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy($mac, Request $request) //elimina un registro
    {
        $eq = StockEquipment::where('mac', $mac)->firstOrFail();

        $equipment = EquipmentDisuse::find($mac);
        if ($equipment) //verifica si hay un regisro e eliminados con la misma mac
            return redirect()->route('stock.index')->with('warning', 'Ya existe un registro eliminado con el mismo ID.');

        EquipmentDisuse::create([
            'id' => $eq->mac,
            'mark' => $eq->mark,
            'model' => $eq->model,
            'equipment' => $eq->equipment . ' stock',
            'location' => 'No Aplica',
            'description' => $request->input('deletion_description')
        ]);
        StockEqDisuse::create([
            'id' => $eq->mac,
            'delivery_note' => $eq->delivery_note

        ]);
        $eq->delete();
        return redirect()->route('stock.index')->with('success', 'Equipo eliminado exitosamente');
    }
}

<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraInventoriesDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\networkInfrastructure\CameraInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;
use function app\Helpers\marksUpdate;

/* controlador para el 
crud de camaras en inventario */

class CameraInventoriesController extends Controller
{
    //
    public function index(Request $request) //lista los registros 
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('mark') ||
            $request->filled('delivery_note');

        if (!$hasFilters) {
            $cameras = CameraInventory::orderBy('created_at', 'desc')->paginate(10);

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas

            return view('front.camera.camera_inventories.index', compact('cameras', 'marks'));
        }

        return filter($request, 'camera_inventories');
    }

    public function create() //muestra el formulario 
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
        return view('front.camera.camera_inventories.create', compact('marks'));
    }

    public function store(Request $request) // registra un nuevo registro
    {

        $validator = Validator::make($request->all(), [
            'mac' => 'required|unique:camera_inventories',
            'model' => 'required',
            'mark' => 'required',
            'delivery_note' => 'required',
            'destination' => 'required',
            'description' => 'nullable'

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = marksUpdate($request, 'marks');

        CameraInventory::create($request->all())->save();
        return redirect()->route('inventories.index')->with('succes', 'Caámara eliminada exitosamente');
    }

    public function destroy($mac, Request $request) //elimina un registro
    {
        $camera = CameraInventory::findOrFail($mac);

        $equipment = EquipmentDisuse::find($mac);
        if ($equipment)
            return redirect()->route('inventories.index')->with('success', 'Ya existe un registro eliminado con el mismo ID.');

        EquipmentDisuse::create([
            'id' => $camera->mac,
            'mark' => $camera->mark,
            'model' => $camera->model,
            'equipment' => 'Cámara',
            'location' => 'No Aplica',
            'description' => $request->input('deletion_description')
        ]);

        CameraInventoriesDisuse::create([
            'id' => $camera->mac,
            'destination' => $camera->destination,
            'delivery_note' => $camera->delivery_note

        ]);
        $camera->delete();
        return redirect()->route('inventories.index')->with('success', 'Cámara eliminada exitosamente');
    }
}

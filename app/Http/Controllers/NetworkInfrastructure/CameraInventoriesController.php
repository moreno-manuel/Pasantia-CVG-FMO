<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraInventoriesDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\networkInfrastructure\CameraInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function app\Helpers\filter;

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
            return view('front.camera.camera_inventories.index', compact('cameras'));
        }

        return filter($request, 'camera_inventories');
    }

    public function create() //muestra el formulario 
    {
        return view('front.camera.camera_inventories.create');
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

        CameraInventory::create($request->all())->save();

        return redirect()->route('inventories.index')->with('succes', 'Caámara eliminada exitosamente');
    }

    public function destroy($mac, Request $request) //elimina un registro
    {
        $camera = CameraInventory::find($mac);

        EquipmentDisuse::create([
            'id' => $camera->mac,
            'model' => $camera->model,
            'equipment' => 'camara_inventories',
            'location' => 'No Aplica',
            'description' => $request->input('deletion_description')
        ]);

        CameraInventoriesDisuse::create([
            'id' => $camera->mac,
            'mark' => $camera->mark,
            'destination' => $camera->destination,
            'delivery_note' => $camera->delivery_note

        ]);
        $camera->delete();
        return redirect()->route('inventories.index')->with('succes', 'Caámara eliminada exitosamente');
    }
}

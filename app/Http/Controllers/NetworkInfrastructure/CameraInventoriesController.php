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

    public function index(Request $request)
    {
        $hasFilters = $request->filled('mark') ||
            $request->filled('delivery_note');

        if (!$hasFilters) { // Valida si hay algún filtro activo
            $cameras = CameraInventory::select('mac', 'mark', 'model', 'delivery_note', 'destination', 'description')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas

            return view('front.camera.camera_inventories.index', compact('cameras', 'marks'));
        }

        return filter($request, 'camera_inventories');
    }

    public function create()
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
        return view('front.camera.camera_inventories.create', compact('marks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mac' => 'required|unique:camera_inventories|alpha_num|size:12',
                'model' => 'required|alpha_num|min:3',
                'mark' => 'required',
                'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                'delivery_note' => 'required|numeric|min:3',
                'destination' => 'required|regex:/^[a-zA-Z0-9\/\- ]+$/|min:5',
                'description' => 'nullable'

            ],
            ['required_if' => 'Debe agregar el nombre de la marca'],
            ['location' => 'Localidad', 'model' => 'Modelo', 'delivery_note' => 'Nota de entrega', 'destination' => 'Destino de instalación', 'other_mark' => 'Marca']
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $request = marksUpdate($request, 'marks'); //verifica si hay una marca nueva

        CameraInventory::create($request->all())->save();
        return redirect()->route('inventories.index')->with('success', 'Cámara agregada exitosamente');
    }

    public function destroy($mac, Request $request) //elimina un registro
    {
        $camera = CameraInventory::where('mac', $mac)->firstOrFail();

        $equipment = EquipmentDisuse::find($mac);
        if ($equipment) //verifica si hay un regisro e eliminados con la misma mac
            return redirect()->route('inventories.index')->with('warning', 'Ya existe un registro eliminado con el mismo ID.');

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

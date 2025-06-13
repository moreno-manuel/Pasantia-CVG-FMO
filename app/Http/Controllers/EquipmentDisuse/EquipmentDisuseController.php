<?php

namespace App\Http\Controllers\EquipmentDisuse;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraDisuse;
use App\Models\EquipmentDisuse\CameraInventoriesDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\LinkDisuse;
use App\Models\EquipmentDisuse\NvrDisuse;
use App\Models\EquipmentDisuse\SwitchDisuse;
use Illuminate\Http\Request;

use function app\Helpers\filter;

class EquipmentDisuseController extends Controller
{
    //
    public function index(Request $request)
    {

        if (!$request->filled('equipment')) { //si no se aplica un filtro
            $equipments = EquipmentDisuse::orderBy('created_at', 'desc')->paginate(10);
            return view('front.eliminated.index', compact('equipments'));
        }

        return filter($request, 'equipments');
    }

    public function show($id)
    {
        $equipment = EquipmentDisuse::where('id', $id)->first();

        $camera_inventories = CameraInventoriesDisuse::where('id', $id)->first();
        if ($equipment->id == $camera_inventories->id) { // si la camara pertenece a la tabla de camara_inventories_disuses
            $equipment_type = 'camera_inventories';
            return view('front.eliminated.show', compact('equipment', 'camera_inventories', 'equipment_type'));
        }

        switch ($equipment->equipment) {
            case 'Switch':
                $switch = SwitchDisuse::where('id', $id)->first();
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'switch', 'equipment_type'));
                break;
            case 'Nvr':
                $nvr = NvrDisuse::where('id', $id)->first();
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'nvr', 'equipment_type'));
                break;
            case 'Cámara':
                $camera = CameraDisuse::where('id', $id)->first();
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'camera', 'equipment_type'));
                break;
            case 'Enlace':
                $link = LinkDisuse::where('id', $id)->first();
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'link', 'equipment_type'));
                break;
        }
    }

    public function destroy($id)
    {
        $equipment = EquipmentDisuse::where('id', $id)->first();
        $equipment->delete();
        return redirect()->route('eliminated.index')->with('success', 'Registro eliminado exitosamente.');
    }
}

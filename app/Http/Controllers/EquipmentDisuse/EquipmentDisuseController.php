<?php

namespace App\Http\Controllers\EquipmentDisuse;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\LinkDisuse;
use App\Models\EquipmentDisuse\NvrDisuse;
use App\Models\EquipmentDisuse\StockEqDisuse;
use App\Models\EquipmentDisuse\SwitchDisuse;
use Illuminate\Http\Request;

use function app\Helpers\filter;

/* Controlador para equipos eliminados */

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
        $equipment = EquipmentDisuse::findOrFail($id);

        switch ($equipment->equipment) {
            case 'Switch':
                $switch = SwitchDisuse::find($id);
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'switch', 'equipment_type'));
                break;
            case 'NVR':
                $nvr = NvrDisuse::find($id);
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'nvr', 'equipment_type'));
                break;
            case 'Cámara':
                $camera = CameraDisuse::find($id);
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'camera', 'equipment_type'));
                break;
            case 'Enlace':
                $link = LinkDisuse::find($id);
                $equipment_type = $equipment->equipment;
                return view('front.eliminated.show', compact('equipment', 'link', 'equipment_type'));
                break;
            default:
                $stock = StockEqDisuse::find($id);
                $equipment_type = 'stock';
                return view('front.eliminated.show', compact('equipment', 'stock', 'equipment_type'));
                break;
        }
    }

    public function destroy($id)
    {
        $equipment = EquipmentDisuse::findOrFail($id);
        $equipment->delete();
        return redirect()->route('eliminated.index')->with('success', 'Registro eliminado exitosamente.');
    }
}

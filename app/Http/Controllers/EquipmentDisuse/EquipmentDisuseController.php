<?php

namespace App\Http\Controllers\EquipmentDisuse;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use Illuminate\Http\Request;

use function app\Helpers\filter;

class EquipmentDisuseController extends Controller
{
    //
    public function index(Request $request)
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('equipments');

        if (!$hasFilters) { //si no se aplica un filtro
            $equipments = EquipmentDisuse::orderBy('id', 'desc')->paginate(10);
            return view('front.eliminated.index', compact('equipments'));
        }


        return filter($request, 'equipments');
    }

    public function show(EquipmentDisuse $equipment) {}

    public function destroy($id)
    {
        $equipment = EquipmentDisuse::where('id', $id)->first();
        $equipment->delete();
        return redirect()->route('eliminated.index')->with('success', 'Registro eliminado exitosamente.');
    }
}

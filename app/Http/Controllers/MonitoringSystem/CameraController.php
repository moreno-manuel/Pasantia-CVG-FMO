<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;
use function Pest\Laravel\get;

/* controlador para el 
crud de Camara */

class CameraController extends Controller
{
    //

    public function index(Request $request) //muestra tabla con registros de camara
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('location') ||
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $cameras = Camera::paginate(10);
            return view('front.camera.index', compact('cameras'));
        }

        return filter($request, 'cameras'); //helper
    }

    public function create()
    {
        $nvrsAll = Nvr::all(); //regitros de todos los nvrs

        // Filtrar la colección para mantener solo los NVRs con puertos disponibles
        $nvrs = $nvrsAll->filter(function ($nvr) {
            $ports_used = $nvr->camera->count();
            $available_ports = $nvr->ports_number - $ports_used;
            return $available_ports > 0; // Mantener si hay puertos disponibles
        });


        return view('front.camera.create', compact('nvrs'));
    }

    public function store(Request $request) //valida los datos para un nuevo registro
    {
        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mac' => 'required|unique:cameras,mac',
                'mark' => 'required',
                'nvr_id' => 'required',
                'model' => 'required',
                'name' => 'required|unique:cameras,name',
                'location' => 'required',
                'ip' => 'required|ip|unique:cameras,ip',
                'status' => 'required',
                'description' => 'nullable'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $camera = Camera::create($request->all());
            $camera->save();
            return redirect()->route('camara.index')->with('success', 'Camara agregada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function destroy($mac) //elimina un registro
    {
        // Recupera el modelo manualmente
        $camera = Camera::where('mac', $mac)->first();
        $camera->delete();
        return redirect()->route('camara.index')->with('success', 'Camara eliminado exitosamente.');
    }

    public function show($mac) //muestra detalles de un registro 
    {
        // Recupera el modelo manualmente
        $camera = Camera::where('mac', $mac)->firstOrFail();

        // Cargar los registros con paginación
        $conditions = $camera->conditionAttention()->paginate(5);

        return view('front.camera.show', compact('camera', 'conditions'));
    }

    public function update(Request $request, $mac) //valida los datos d edicion
    {
        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mark' => 'required',
                'nvr_id' => 'required',
                'model' => 'required',
                'name' => 'required',
                'location' => 'required',
                'ip' => 'required|ip|unique:cameras,ip',
                'status' => 'required',
                'description' => 'nullable'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $camera = Camera::where('mac', $mac)->first();
            $camera->update($request->all());
            return redirect()->route('camara.index')->with('success', 'Camara agregada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function edit($mac) //muestra la vista para editar
    {
        $nvrsAll = Nvr::all(); //regitros de todos los nvrs

        // Filtrar la colección para mantener solo los NVRs con puertos disponibles
        $nvrs = $nvrsAll->filter(function ($nvr) {
            $ports_used = $nvr->camera->count();
            $available_ports = $nvr->ports_number - $ports_used;
            return $available_ports > 0; // Mantener si hay puertos disponibles
        });

        // Recupera el modelo manualmente
        $camera = Camera::where('mac', $mac)->first();
        return view('front.camera.edit', compact('camera', 'nvrs'));
    }
}

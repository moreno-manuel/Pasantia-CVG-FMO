<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;
use function app\Helpers\marksUpdate;

/* controlador para el 
crud de Camara */

class CameraController extends Controller
{
    //

    public function index(Request $request) //muestra tabla con registros de camara
    {
        $hasFilters = $request->filled('location') ||    // Valida si hay algún filtro  de busqueda activo
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $cameras = Camera::orderBy('created_at', 'desc')->paginate(10);
            return view('front.camera.index', compact('cameras'));
        }

        return filter($request, 'cameras'); //helper
    }

    public function create() // muestra formulario para nuevo registro
    {
        $nvrsAll = Nvr::all(); //regitros de todos los nvrs

        $nvrs = $nvrsAll->filter(function ($nvr) {   // Filtrar la colección para mantener solo los NVRs con puertos disponibles
            $ports_used = $nvr->camera->count();
            $available_ports = $nvr->ports_number - $ports_used;
            return $available_ports > 0; // Mantener si hay puertos disponibles
        });

        $marks = json_decode(file_get_contents(resource_path('js/marks.json')), true)['marks']; // json con las marcas agregadas

        return view('front.camera.create', compact('nvrs', 'marks'));
    }

    public function store(Request $request) //valida los datos para un nuevo registro
    {
        try {
            $validator = Validator::make($request->all(), [
                'mac' => 'required|unique:cameras,mac',
                'mark' => 'required',
                'other_mark' => 'required_if:mark,OTRA',
                'nvr_id' => 'required',
                'model' => 'required',
                'name' => 'required|unique:cameras,name',
                'location' => 'required',
                'ip' => 'required|ip|unique:cameras,ip',
                'status' => 'required',
                'description' => 'nullable'
            ], ['required_if' => 'Debe agregar el nombre de la marca']);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            // si se agrega una nueva marca
            $request = marksUpdate($request);

            Camera::create($request->all())->save();
            return redirect()->route('camara.index')->with('success', 'Cámara agregada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function edit($mac) //muestra la vista para editar
    {
        $nvrsAll = Nvr::all(); //regitros de todos los nvrs

        $nvrs = $nvrsAll->filter(function ($nvr) {  // Filtrar la colección para mantener solo los NVRs con puertos disponibles
            $ports_used = $nvr->camera->count();
            $available_ports = $nvr->ports_number - $ports_used;
            return $available_ports > 0; // Mantener si hay puertos disponibles
        });

        $marks = json_decode(file_get_contents(resource_path('js/marks.json')), true)['marks']; // json con las marcas agregadas

        // Recupera el modelo manualmente
        $camera = Camera::find($mac);
        return view('front.camera.edit', compact('camera', 'nvrs', 'marks'));
    }

    public function update(Request $request, $mac) //valida los datos d edicion
    {
        try {
            $camera = Camera::find($mac);

            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mark' => 'required',
                'other_mark' => 'required_if:mark,OTRA',
                'nvr_id' => 'required',
                'model' => 'required',
                'name' => [
                    'required',
                    Rule::unique('cameras')->ignore($camera->mac, 'mac') //ignora el nombre registro que va actualizar 
                ],
                'location' => 'required',
                'ip' => 'required|ip|unique:cameras,ip',
                'status' => 'required',
                'description' => 'nullable'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            // si se agrega una nueva marca
            $request = marksUpdate($request);

            $camera->update($request->all());
            return redirect()->route('camara.index')->with('success', 'Cámara agregada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function show($mac) //muestra detalles de un registro 
    {
        $camera = Camera::find($mac);

        $conditions = $camera->conditionAttention()->orderBy('created_at', 'desc')->paginate(5); // Cargar los registros de condicion de atención con paginación

        return view('front.camera.show', compact('camera', 'conditions'));
    }

    public function destroy(Request $request, $mac) //elimina un registro
    {

        $camera = Camera::find($mac);

        EquipmentDisuse::create([
            'id' => $camera->mac,
            'model' => $camera->model,
            'location' => $camera->location,
            'equipment' => 'Cámara',
            'description' => $request->input('deletion_description')
        ]);

        $nvr = $camera->nvr;

        CameraDisuse::create([
            'id' => $camera->mac,
            'name' => $camera->name,
            'nvr_name' => $camera->mac,
            'mark' => $camera->mark,
            'ip' => $nvr->name
        ]);

        $camera->delete();
        return redirect()->route('camara.index')->with('success', 'Cámara eliminado exitosamente.');
    }
}

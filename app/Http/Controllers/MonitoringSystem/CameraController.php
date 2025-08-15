<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\CameraDisuse;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use function app\Helpers\filter;
use function app\Helpers\locationUpdate;
use function app\Helpers\marksUpdate;

/* controlador para el 
crud de Camara */

class CameraController extends Controller
{


    public function index(Request $request)
    {
        $hasFilters = $request->filled('location') || $request->filled('model') ||
            $request->filled('status');

        if (!$hasFilters) { //si no se aplica un filtro
            $cameras = Camera::with('nvr')
                ->select('name', 'ip', 'location', 'mac', 'status', 'mark', 'model', 'nvr_id')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
            return view('front.camera.index', compact('cameras', 'locations'));
        }

        return filter($request, 'cameras'); //helper
    }

    public function create()
    {
        $nvrsAll = Nvr::with('camera')->orderBy('name', 'asc')->get();

        $nvrs = $nvrsAll->filter(function ($nvr) { // Filtrar la colección para mantener solo los NVRs con puertos disponibles
            return $nvr->available_ports > 0;
        });

        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
        $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
        return view('front.camera.create', compact('nvrs', 'marks', 'locations'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'mac' => 'required|unique:cameras,mac|alpha_num|min:10|max:12',
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'nvr_id' => 'required',
                    'model' => 'required|alpha_dash|min:3',
                    'name' => 'required|unique:cameras,name|regex:/^[a-zA-Z0-9Ññ\/\-. ]+$/|min:5',
                    'location' => 'required',
                    'other_location' => 'nullable|regex:/^[a-zA-Z0-9Ññ\/\-. ]+$/|min:5|required_if:location,Otra',
                    'ip' => 'required|ip|unique:cameras,ip',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de :attribute'],
                ['name' => 'Nombre', 'location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca']
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $request = marksUpdate($request, 'marks');
            $request = locationUpdate($request, 'locations'); //si hay una localidad nueva

            Camera::create($request->all());
            return redirect()->route('camara.index')->with('success', 'Cámara agregada exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function edit($name)
    {
        try {

            $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
            if ($redirectRoute != 'camara.edit')
                session(['urlCamera' => url()->previous()]); //captura ruta desde donde se llama el metodo

            $camera = Camera::where('name', $name)->firstOrFail();
            $nvrsAll = Nvr::with('camera')->get();

            $currentNvrId = $camera->nvr->mac;

            $nvrs = $nvrsAll->filter(function ($nvr) use ($currentNvrId) {
                if ($nvr->mac == $currentNvrId) { //mantiene el nvr de la cámara seleccionada 
                    return true;
                }
                return $nvr->available_ports > 0;
            });

            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas
            $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
            return view('front.camera.edit', compact('camera', 'nvrs', 'marks', 'locations'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('camera.index')->with('warnings', 'Cámara no encontrada');
        }
    }

    public function update(Request $request, $mac)
    {
        try {
            $camera = Camera::where('mac', $mac)->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'nvr_id' => 'required',
                    'model' => 'required|alpha_dash|min:3',
                    'name' => [
                        'required',
                        'regex:/^[a-zA-Z0-9Ññ\/\-. ]+$/',
                        'min:5',
                        Rule::unique('cameras')->ignore($camera->mac, 'mac') //ignora el nombre registro que va actualizar 
                    ],
                    'location' => 'required',
                    'other_location' => 'nullable|regex:/^[a-zA-Z0-9Ññ\/\-. ]+$/|min:5|required_if:location,Otra',
                    'ip' => 'required|ip|unique:cameras,ip',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de :attribute'],
                ['location' => 'Localidad', 'model' => 'Modelo', 'other_mark' => 'Marca']
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $request = marksUpdate($request, 'marks');
            $request = locationUpdate($request, 'locations'); //si hay una localidad nueva

            $camera->update($request->all());

            $redirectRoute = Route::getRoutes()->match(app('request')->create(session('urlCamera')))->getName();
            if ($redirectRoute != 'camara.show')
                return redirect(session('urlCamera'))->with('success', 'Cámara actualizada.');

            return redirect()->route('camara.show', ['camara' => $camera->name])->with('success', 'Cámara actualizada.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function show($name)
    {
        try {
            $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
            if (($redirectRoute != 'camara.edit') && ($redirectRoute != 'atencion.show') && ($redirectRoute != 'atencion.edit'))
                session(['urlCameraShow' => url()->previous()]);

            $camera = Camera::where('name', $name)->firstOrFail();
            $conditions = $camera->conditionAttention()->orderBy('created_at', 'desc')->paginate(5); // Cargar los registros de condicion de atención con paginación

            return view('front.camera.show', compact('camera', 'conditions'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('camara.index')->with('warnings', 'Cámara  no encontrada');
        }
    }

    public function destroy(Request $request, $mac)
    {
        $camera = Camera::where('mac', $mac)->firstOrFail();
        $equipment = EquipmentDisuse::find($mac);
        if ($equipment) {
            return redirect(url()->previous())->with('warning', ' existe un registro eliminado con el mismo ID.');
        }


        EquipmentDisuse::create([
            'id' => $camera->mac,
            'mark' => $camera->mark,
            'model' => $camera->model,
            'location' => $camera->location,
            'equipment' => 'Cámara',
            'description' => $request->input('deletion_description')
        ]);
        $nvr = $camera->nvr->mac . " - " . $camera->nvr->name; // para almecenar mac y nombre del nvr 
        CameraDisuse::create([
            'id' => $camera->mac,
            'name' => $camera->name,
            'nvr' => $nvr,
            'ip' => $camera->ip
        ]);

        $camera->delete();

        $redirectRoute = Route::getRoutes()->match(app('request')->create(url()->previous()))->getName();
        if ($redirectRoute === 'camara.index') {
            return back()->with('success', 'Cámara eliminada exitosamente.');
        } else if (session()->has('urlCameraShow')) {
            return redirect(session('urlCameraShow'))->with('success', 'Cámara eliminada exitosamente.');
        } else {
            return redirect(url()->previous())->with('success', 'Cámara eliminada exitosamente.');
        }
    }
}

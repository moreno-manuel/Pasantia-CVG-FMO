<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\LinkDisuse;
use App\Models\networkInfrastructure\Link;
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
crud del Link (enlace) */

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $hasFilters = $request->filled('location') || $request->filled('model');

        if (!$hasFilters) {  // Valida si hay algún filtro activo
            $links = Link::select('mac', 'name', 'mark', 'model', 'ssid', 'location', 'ip')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
            return view('front.link.index', compact('links', 'locations'));
        }

        return filter($request, 'links'); //helper
    }

    public function create()
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['link_marks']; // json con las marcas agregadas
        $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
        return view('front.link.create', compact('marks', 'locations'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'mac' => 'required|unique:links,mac|alpha_num|size:12',
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'model' => 'required|alpha_dash|min:3',
                    'name' => 'required|unique:links,name|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5',
                    'ssid' => 'required|alpha_num|min:3',
                    'location' => 'required',
                    'other_location' => 'nullable|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5|required_if:location,Otra',
                    'ip' => 'required|ip|unique:links,ip',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de :attribute'],
                ['name' => 'Nombre', 'location' => 'Localidad', 'model' => 'Modelo', 'ssid' => 'SSID', 'other_mark' => 'Marca']
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $request = marksUpdate($request, 'link_marks');
            $request = locationUpdate($request, 'locations'); //si hay una localidad nueva

            Link::create($request->all())->save();
            return redirect()->route('enlace.index')->with('success', 'Enlace agregado exitosamente.');
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
            if ($redirectRoute != 'enlace.edit')
                session(['linkUrl' => url()->previous()]);

            $link = Link::where('name', $name)->firstOrFail();
            $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['link_marks']; // json con las marcas agregadas
            $locations = json_decode(file_get_contents(resource_path('js/data.json')), true)['locations']; // json con las localidades agregadas
            return view('front.link.edit', compact('link', 'marks', 'locations'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('enlace.index')->with('warnings', 'Enlace no encontrado');
        }
    }

    public function update(Request $request,  $mac)
    {
        try {
            $link = Link::where('mac', $mac)->firstOrFail();

            $validator = Validator::make(
                $request->all(),
                [
                    'model' => 'required|alpha_dash|min:3',
                    'name' => [
                        'required',
                        'regex:/^[a-zA-Z0-9\/\-. ]+$/',
                        'min:5',
                        Rule::unique('links')->ignore($link->mac, 'mac') //ignora el registro que va actualizar 
                    ],
                    'mark' => 'required',
                    'other_mark' => 'nullable|alpha_num|min:3|required_if:mark,Otra',
                    'ssid' => 'required|alpha_num|min:3',
                    'location' => 'required',
                    'other_location' => 'nullable|regex:/^[a-zA-Z0-9\/\-. ]+$/|min:5|required_if:location,Otra',
                    'ip' => 'required|ip|unique:links,ip',
                    'description' => 'nullable'
                ],
                ['required_if' => 'Debe agregar el nombre de :attribute'],
                ['name' => 'Nombre', 'location' => 'Localidad', 'model' => 'Modelo', 'ssid' => 'SSID', 'other_mark' => 'Marca']
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $request = marksUpdate($request, 'link_marks');
            $request = locationUpdate($request, 'locations'); //si hay una localidad nueva

            $link->update($request->all());

            $redirectRoute = Route::getRoutes()->match(app('request')->create(session('linkUrl')))->getName();
            if ($redirectRoute === 'enlace.show')
                return redirect()->route('enlace.show', ['enlace' => $link->name])->with('success', 'Enlace actualizado.');

            return redirect()->route('enlace.index')->with('success', 'Enlace actualizado.');
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
            $link = Link::where('name', $name)->firstOrFail();
            return view('front.link.show', compact('link'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('enlace.index')->with('warnings', 'Enlace no encontrado');
        }
    }

    public function destroy(Request $request, $mac)
    {
        $link = Link::where('mac', $mac)->firstOrFail();

        $equipment = EquipmentDisuse::find($link->mac);
        if ($equipment)
            return redirect()->route('enlace.index')->with('warning', 'Ya existe un registro eliminado con el mismo ID.');

        EquipmentDisuse::create([
            'id' => $link->mac,
            'mark' => $link->mark,
            'model' => $link->model,
            'location' => $link->location,
            'equipment' => 'Enlace',
            'description' => $request->input('deletion_description')
        ]);
        LinkDisuse::create([
            'id' => $link->mac,
            'name' => $link->name,
            'ssid' => $link->ssid,
            'ip' => $link->ip
        ]);

        $link->delete();
        return redirect()->route('enlace.index')->with('success', 'Enlace eliminado exitosamente.');
    }
}

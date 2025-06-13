<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\EquipmentDisuse\EquipmentDisuse;
use App\Models\EquipmentDisuse\LinkDisuse;
use App\Models\networkInfrastructure\Link;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;
use function app\Helpers\marksUpdate;

//controlador para el crud del Link (enlace)
class LinkController extends Controller
{
    public function index(Request $request) //muestra los registros en la tabla principal link
    {
        // Valida si hay algún filtro activo
        $hasFilters = $request->filled('status') ||
            $request->filled('location');

        if (!$hasFilters) { //si no se aplica un filtro
            $links = Link::orderBy('created_at', 'desc')->paginate(10);
            return view('front.link.index', compact('links'));
        }

        return filter($request, 'links'); //helper
    }

    public function create() //muestra la vista para crear un nuevo link
    {
        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas

        return view('front.link.create', compact('marks'));
    }

    public function store(Request $request) //guarda los datos de un link nuevo
    {
        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mac' => 'required|unique:links,mac',
                'mark' => 'required',
                'other_mark' => 'required_if:mark,OTRA',
                'model' => 'required',
                'name' => 'required|unique:links,name',
                'ssid' => 'required',
                'location' => 'required',
                'status' => 'required',
                'ip' => 'required|ip|unique:links,ip',
                'description' => 'nullable'
            ], ['required_if' => 'Debe agregar el nombre de la marca']);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            // si se agrega una nueva marca
            $request = marksUpdate($request);


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

    public function edit($mac) //muestra la vista para editar un link
    {
        $link = Link::find($mac);

        $marks = json_decode(file_get_contents(resource_path('js/data.json')), true)['marks']; // json con las marcas agregadas

        return view('front.link.edit', compact('link', 'marks'));
    }

    public function update(Request $request,  $mac) //Actualiza los datos de un link
    {

        try {
            $link = Link::find($mac);

            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'model' => 'required',
                'name' => [
                    'required',
                    Rule::unique('links')->ignore($link->mac, 'mac') //ignora el registro que va actualizar 
                ],
                'mark' => 'required',
                'other_mark' => 'required_if:mark,Otra',
                'ssid' => 'required',
                'location' => 'required',
                'status' => 'required',
                'ip' => 'required|ip|unique:links,ip',
                'description' => 'nullable'
            ], ['required_if' => 'Debe agregar el nombre de la marca']);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            // si se agrega una nueva marca
            if ($request->filled('other_mark')) {
                $request = marksUpdate($request);
            }

            $link->update($request->all());
            return redirect()->route('enlace.index');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                return redirect()->back()->withInput()->withErrors([
                    'ip' => 'La dirección IP ya está en uso.',
                ]);
            }
        }
    }

    public function show($mac) //muestra la vista y datos para los detalles un link
    {
        $link = Link::find($mac);
        return view('front.link.show', compact('link'));
    }

    public function destroy(Request $request, $mac) //Elimina un link
    {
        $link = Link::find($mac);

        $equipment = EquipmentDisuse::find($mac);
        if ($equipment)
            return redirect()->route('enlace.index')->with('success', 'Ya existe un registro eliminado con el mismo ID.');

        EquipmentDisuse::create([
            'id' => $link->mac,
            'model' => $link->model,
            'location' => $link->location,
            'equipment' => 'Enlace',
            'description' => $request->input('deletion_description')
        ]);

        LinkDisuse::create([
            'id' => $link->mac,
            'name' => $link->name,
            'ssid' => $link->ssid,
            'mark' => $link->mark,
            'ip' => $link->ip
        ]);

        $link->delete();
        return redirect()->route('enlace.index')->with('success', 'Enlace eliminado exitosamente.');
    }
}

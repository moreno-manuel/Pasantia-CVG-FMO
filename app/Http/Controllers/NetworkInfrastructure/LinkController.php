<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\networkInfrastructure\Link;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use function app\Helpers\filter;

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
        return view('front.link.create');
    }

    public function store(Request $request) //guarda los datos de un link nuevo
    {
        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mac' => 'required|unique:links',
                'mark' => 'required',
                'model' => 'required',
                'name' => 'required|unique:links',
                'ssid' => 'required',
                'location' => 'required',
                'status' => 'required',
                'ip' => 'required|ip|unique:links',
                'description' => 'nullable'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            Link::create($request->all())->save();

            return redirect()->route('enlace.index')->with('success', 'Enlace agregado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }

    public function destroy($mac) //Elimina un link
    {
        // Recupera el modelo manualmente
        $link = Link::where('mac', $mac)->first();
        $link->delete();
        return redirect()->route('enlace.index')->with('success', 'Enlace eliminado exitosamente.');
    }

    public function show($mac) //muestra la vista y datos para los detalles un link
    {
        $link = Link::where('mac', $mac)->firstOrFail();
        return view('front.link.show', compact('link'));
    }

    public function edit($mac) //muestra la vista para editar un link
    {
        $link = Link::where('mac', $mac)->firstOrFail();
        return view('front.link.edit', compact('link'));
    }

    public function update(Request $request,  $mac) //Actualiza los datos de un link
    {
        try {
            $validator = Validator::make($request->all(), [ //para capturar si hay dato incorrecto
                'mark' => 'required',
                'model' => 'required',
                'name' => 'required',
                'ssid' => 'required',
                'location' => 'required',
                'status' => 'required',
                'ip' => 'required|ip|unique:links',
                'description' => 'nullable'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $link = Link::where('mac', $mac)->first();
            $link->update($request->all());
            return redirect()->route('enlace.index');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Código de error de integridad para la db *IP*
                throw ValidationException::withMessages([
                    'ip' => ['La dirección IP ya está en uso.'],
                ]);
            }
        }
    }
}

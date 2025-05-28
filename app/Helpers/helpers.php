<?php

namespace app\Helpers;

use App\Models\networkInfrastructure\Link;
use App\Models\networkInfrastructure\Switche;
use Illuminate\Http\Request;

/* busqueda filtrada
--el string recibe el nombre 
del bloque que contiene 
la tabla y filtros
para la busqueda*/

function filter(Request $request, string $table)
{
    
    switch ($table) {
        case 'switches': {
                // Obtén los valores de los filtros
                $name = strtoupper($request->input('serial'));
                $model = strtoupper($request->input('model'));
                $mark = $request->input('number_ports');

                // Construye la consulta base
                $query = Switche::query();

                // Aplica filtros condicionalmente
                if ($name) {
                    $query->where('serial', 'like',  $name . '%');
                }

                if ($model) {
                    $query->where('model', 'like',  $model . '%');
                }

                if ($mark) {
                    $query->where('number_ports', $mark);
                }

                // Ejecuta la consulta y aplica paginación
                $switches = $query->paginate(5);

                // Mantiene los valores de los filtros en la vista
                return view('front.switch.index', compact('switches'))
                    ->with('filters', $request->all());
                break;
            }


        case 'links': {
                // Obtén los valores de los filtros
                $name = strtoupper($request->input('name'));
                $model = strtoupper($request->input('model'));
                $mark = strtoupper($request->input('mark'));

                // Construye la consulta base
                $query = Link::query();

                // Aplica filtros condicionalmente
                if ($name) {
                    $query->where('name', 'like',  $name . '%');
                }

                if ($model) {
                    $query->where('model', 'like',  $model . '%');
                }

                if ($mark) {
                    $query->where('mark', 'like',  $mark . '%');
                }

                // Ejecuta la consulta y aplica paginación
                $links = $query->paginate(5);

                // Mantiene los valores de los filtros en la vista
                return view('front.link.index', compact('links'))
                    ->with('filters', $request->all());
                break;


                break;
            }
        default:
            # code...
            break;
    }
}

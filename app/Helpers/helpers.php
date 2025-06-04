<?php

namespace app\Helpers;

use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\Nvr;
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
                // Obtén el valor del filtro
                $serial = $request->input('serial');
                $location = $request->input('location');
                $status = $request->input('status');

                // Construye la consulta base
                $query = Switche::query();

                // Aplica filtros condicionalmente
                if ($serial) {
                    $query->where('serial', 'like',  $serial . '%');
                }

                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }

                // Ejecuta la consulta y aplica paginación
                $switches = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.switch.index', compact('switches'))
                    ->with('filters', $request->all());
                break;
            }


        case 'links': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Link::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $links = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.link.index', compact('links'))
                    ->with('filters', $request->all());
                break;
            }

        case 'nvrs': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Nvr::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $nvrs = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.nvr.index', compact('nvrs'))
                    ->with('filters', $request->all());
                break;
            }
        case 'cameras': {
                // Obtén los valores de los filtros
                $location = $request->input('location');
                $status = $request->input('status');


                // Construye la consulta base
                $query = Camera::query();

                // Aplica filtros condicionalmente
                if ($location) {
                    $query->where('location', 'like',  $location . '%');
                }

                // Aplica filtros condicionalmente
                if ($status) {
                    $query->where('status', 'like',  $status . '%');
                }


                // Ejecuta la consulta y aplica paginación
                $cameras = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.camera.index', compact('cameras'))
                    ->with('filters', $request->all());
                break;
            }
        case 'conditions': {
                // Obtén los valores de los filtros
                $name = $request->input('name');

                // Construye la consulta base
                $query = ConditionAttention::query();

                // Aplica filtros condicionalmente
                if ($name) {
                    $query->where('name',  $name);
                }

                // Ejecuta la consulta y aplica paginación
                $conditions = $query->orderBy('created_at', 'desc')->paginate(10);

                // Mantiene los valores de los filtros en la vista
                return view('front.attention.index', compact('conditions'))
                    ->with('filters', $request->all());
                break;
            }
        default:
            return 'Error en helpers o controlador';
            break;
    }
}

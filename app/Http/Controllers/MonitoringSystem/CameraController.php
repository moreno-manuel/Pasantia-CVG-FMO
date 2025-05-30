<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Models\monitoringSystem\Camera;
use Illuminate\Http\Request;

use function app\Helpers\filter;

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
        return view('front.camera.create');
    }

    public function store(Request $request)
    {
        return $request;
    }
}

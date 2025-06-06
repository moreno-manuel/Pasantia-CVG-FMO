<?php

namespace App\Http\Controllers\NetworkInfrastructure;

use App\Http\Controllers\Controller;
use App\Models\networkInfrastructure\Camera_inventory;
use Illuminate\Http\Request;

/* controlador para el 
crud de camaras en inventario */

class CameraInventoriesController extends Controller
{
    //
    public function index()
    {
        $cameras = Camera_inventory::orderBy('created_at', 'desc')->paginate(10);
        return view('front.camera.camera_inventories.index', compact('cameras'));
    }
    public function create()
    {
        return view('front.camera.camera_inventories.create');
    }
    public function store() {}
    public function destroy() {}
}

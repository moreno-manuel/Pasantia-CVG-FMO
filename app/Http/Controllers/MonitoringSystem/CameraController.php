<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    //

    public function create(){
        return view('front.camera.create');
    }

    public function index()
    {
        return view('front.camera.index');
    }
}

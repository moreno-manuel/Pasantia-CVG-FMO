<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

    public function index()
    {
        $reports = [

            ['name' => 'Inventario Cámaras por Nvr', 'url' => 'report.cameraByNvr'],
            ['name' => 'Inventario Cámaras en stock', 'url' => 'report.cameraStock'],
            ['name' => 'Inventario Switches', 'url' => 'report.switch'],
            ['name' => 'Inventario Enlaces', 'url' => 'report.link'],
            ['name' => 'Informe Final', 'url' => 'report.report'],

        ];
        return view('front.report.index', compact('reports'));
    }

    public function cameraByNvr()
    {
        return 'prueba';
    }
    public function switch()
    {
        return 'prueba';
    }
    public function link() {}
    public function cameraStock() {}
    public function report() {}
}

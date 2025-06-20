<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

    public function index()
    {
        $reports = [

            ['name' => 'Inventario Cámaras por Nvr', 'url' => '#'],
            ['name' => 'Inventario Cámaras en stock', 'url' => '#'],
            ['name' => 'Inventario Switches', 'url' => '#'],
            ['name' => 'Inventario Enlaces', 'url' => '#'],
            ['name' => 'Informe Final', 'url' => '#'],

        ];
        return view('front.report.index', compact('reports'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\CameraExport;
use App\Exports\CameraStockExport;
use App\Exports\LinkExport;
use App\Exports\NvrExport;
use App\Exports\SwitchExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            ['name' => 'Inventario Nvr', 'url' => 'export.nvr'],
            ['name' => 'Inventario Cámaras', 'url' => 'export.camera'],
            ['name' => 'Inventario Cámaras en stock', 'url' => 'export.cameraStock'],
            ['name' => 'Inventario Switches', 'url' => 'export.switch'],
            ['name' => 'Inventario Enlaces', 'url' => 'export.link'],
            ['name' => 'Informe Final', 'url' => 'export.report'],
        ];

        return view('front.report.index', compact('reports'));
    }


    public function exportCamera()
    {
        return Excel::download(new CameraExport(), 'Inventario_Camara.xlsx');
    }
    public function exportNvr()
    {
        return Excel::download(new NvrExport(), 'Inventario_Nvr.xlsx');
    }

    public function exportCameraStock()
    {
        return Excel::download(new CameraStockExport(), 'Inventario_Camaras_Stock.xlsx');
    }

    public function exportSwitch()
    {
        return Excel::download(new SwitchExport(), 'Inventario_Switches.xlsx');
    }

    public function exportLink()
    {
        return Excel::download(new LinkExport(), 'Inventario_Enlaces.xlsx');
    }

    public function exportReport() {}
}

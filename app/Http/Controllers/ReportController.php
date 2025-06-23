<?php

namespace App\Http\Controllers;

use App\Exports\LinkExport;
use App\Exports\SwitchExport;
use Maatwebsite\Excel\Excel as ExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            ['name' => 'Inventario Cámaras por Nvr', 'url' => 'export.cameraByNvr'],
            ['name' => 'Inventario Cámaras en stock', 'url' => 'export.cameraStock'],
            ['name' => 'Inventario Switches', 'url' => 'export.switch'],
            ['name' => 'Inventario Enlaces', 'url' => 'export.link'],
            ['name' => 'Informe Final', 'url' => 'export.report'],
        ];

        return view('front.report.index', compact('reports'));
    }


    public function exportCameraByNvr() {}


    public function exportCameraStock() {}


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

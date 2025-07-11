<?php

namespace App\Http\Controllers;

use App\Exports\CameraExport;
use App\Exports\CameraStockExport;
use App\Exports\EquipmentDisuse\ReportEquipmentDisuse;
use App\Exports\LinkExport;
use App\Exports\NvrExport;
use App\Exports\ReportFinalExport\ReportFinalExport;
use App\Exports\SwitchExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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
            ['name' => 'Equipos Eliminados', 'url' => 'export.EquipmentDisuse'],
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

    public function exportEquipmentDisuse()
    {
        return Excel::download(new ReportEquipmentDisuse(), 'Equipos_Eliminados.xlsx');
    }

    public function exportReport()
    {
        return Excel::download(new ReportFinalExport(), 'Informe_Final.xlsx');
    }

    public function exportLog(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        // Construir consulta base
        $query = DB::table('activity_log');

        if ($start_date && $end_date) {   //Ambas fechas
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()
            ]);
        } elseif ($start_date) { // Solo fecha de inicio
            $query->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay());
        } elseif ($end_date) { // Solo fecha de fin
            $query->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay());
        }

        $records = $query->get(); //consulta

        // Si no hay registros, devolver archivo vacío o mensaje
        if ($records->isEmpty()) {
            return Response::make("No se encontraron registros.", 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="log_vacio_' . now()->format('Ymd_His') . '.txt"',
            ]);
        }

        $content = '';


        foreach ($records as $record) {

            $user = User::find($record->subject_id);    // Buscar el usuario por subject_id
            $userName = $user ? $user->userName: 'Desconocido';

            // Concatenar al contenido
            $content .= "ID: {$record->id} | Usuario: {$userName} | Descripción: {$record->description} | Objeto: {$record->subject_type} | Evento: {$record->event}  | Propiedades: {$record->properties} | Fecha: {$record->created_at} \n";
        }

        // Devolver el archivo como descarga
        return Response::make($content, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="log_' . now()->format('Ymd_His') . '.txt"',
        ]);
    }
}

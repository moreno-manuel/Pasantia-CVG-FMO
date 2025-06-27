<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Jobs\CheckCameraStatus;
use App\Models\monitoringSystem\Camera;
use Illuminate\Support\Facades\Cache;

class CheckStatusController extends Controller
{
    //

    public function home()
    {
        $cameras = Camera::select(['mac', 'nvr_id', 'name', 'location', 'ip', 'status'])->get(); // Obtén todas las cámaras
        $inactiveCameras = $cameras->filter(function ($camera) {
            return $camera->status == 'Inactivo'; // true si está inactivo
        });


        return view('front.home.checkStatus', compact('inactiveCameras'));
    }

    public function checkStatus()
    {
        //para encolar
        CheckCameraStatus::dispatch();

        $cameras = Camera::select(['mac', 'name', 'nvr_id', 'location', 'status', 'ip'])->get();
        $statuses = [];

        foreach ($cameras as $camera) {
            /*    $cacheKey = 'camera_status_' . $camera->mac;

            // Usar caché para no hacer ping cada vez
            $statusNew = Cache::remember($cacheKey, 10, function () use ($camera) {
                $cmd = "ping -n 1 -w 1000 " . escapeshellarg($camera->ip) . " > nul && echo 1 || echo 0";
                return shell_exec($cmd) == 1 ? 'Activo' : 'Inactivo';
            });  */

            $camera->update([
                'status' =>  Cache::get('camera_status_' . $camera->mac, 'Desconocido')
            ]);

            $statuses[] = [
                'mac' => $camera->mac,
                'name' => $camera->name,
                'location' => $camera->location,
                'nvr' => $camera->nvr->name,
                'ip' => $camera->ip,
                'status' => $camera->status,
            ];
        }

        return response()->json($statuses);
    }
}

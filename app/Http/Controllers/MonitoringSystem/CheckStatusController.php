<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Jobs\CheckDeviceStatusJob;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Support\Facades\Cache;

/* controlador para chequeo de status y otras funcionalidades */

class CheckStatusController extends Controller
{

    public function home()
    {
        $nvr = Nvr::all();
        $nvrAll = $nvr->count();
        $nvrOnline = $nvr->where('status', 'online')->count();
        $nvrOffline = $nvr->where('status', 'offline')->count();
        $nvrConecting = $nvr->where('status', 'conecting...')->count();


        $camera = Camera::all();
        $cameraAll = $camera->count();
        $cameraOnline = $camera->where('status', 'online')->count();
        $cameraOffline = $camera->where('status', 'offline')->count();
        $cameraConecting = $camera->where('status', 'conecting...')->count();


        return view('front.home.home', compact('nvrAll', 'nvrOnline', 'nvrOffline', 'nvrConecting', 'cameraAll', 'cameraOnline', 'cameraOffline', 'cameraConecting'));
    }

    public function test()
    {
        $inactiveCameras = Camera::select(['id', 'mac', 'nvr_id', 'name', 'location', 'ip', 'status'])
            ->where('status', '!=', 'online')
            ->orderBy('location')
            ->paginate(10);

        $inactiveNvr = Nvr::select(['id', 'mac', 'name', 'location', 'ip', 'status'])
            ->where('status', '!=', 'online')
            ->orderBy('location')
            ->paginate(10);

        return view('front.testView.checkStatus', compact('inactiveCameras', 'inactiveNvr'));
    }

    public function checkStatus()
    {
        //para encolar
        CheckDeviceStatusJob::dispatch();


        $data = [
            'cameras' => [],
            'nvrs' => []
        ];

        //procesa camaras 
        $cameras = Camera::all(['id', 'mac', 'name', 'nvr_id', 'location', 'status', 'ip']);
        foreach ($cameras as $camera) {
            $camera->update([
                'status' =>  Cache::get('camera_status_' . $camera->mac, 'conecting...')
            ]);
            if ($camera->status != 'online')
                $data['cameras'][] = [
                    'mac' => $camera->mac,
                    'nvr' => $camera->nvr->name,
                    'name' => $camera->name,
                    'location' => $camera->location,
                    'ip' => $camera->ip,
                    'status' => $camera->status
                ];
        }


        // Procesa NVRs
        $nvrs = Nvr::all(['id', 'mac', 'ip', 'name', 'location', 'status']);
        foreach ($nvrs as $nvr) {
            $nvr->update([
                'status' => Cache::get('nvr_status_' . $nvr->mac, 'conecting...')
            ]);
            if ($nvr->status != 'online')
                $data['nvrs'][] = [
                    'mac' => $nvr->mac,
                    'name' => $nvr->name,
                    'location' => $nvr->location,
                    'ip' => $nvr->ip,
                    'status' => $nvr->status
                ];
        }


        return response()->json($data);
    }

    public function loadCamera($nvr_id)
    {
        $cameras = Camera::where('nvr_id', $nvr_id)
            ->whereDoesntHave('conditionAttention', function ($query) {
                $query->where('status', 'Por atender');
            })
            ->orderBy('name','asc')
            ->get(['id', 'name']);

        return response()->json($cameras);
    }
}

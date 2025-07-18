<?php

namespace App\Http\Controllers\MonitoringSystem;

use App\Http\Controllers\Controller;
use App\Jobs\CheckDeviceStatusJob;
use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\ConditionAttention;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Support\Facades\Cache;

class CheckStatusController extends Controller
{
    //

    public function home()
    {
        $inactiveCameras = Camera::select(['id', 'mac', 'nvr_id', 'name', 'location', 'ip', 'status'])
            ->where('status', '!=', 'online')
            ->orderBy('location')
            ->paginate(10);

        $inactiveNvr = Nvr::select(['id', 'mac', 'name', 'location', 'ip', 'status'])
            ->where('status', '!=', 'online')
            ->orderBy('location')
            ->paginate(10);

        return view('front.home.checkStatus', compact('inactiveCameras', 'inactiveNvr'));
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
            ->get(['id', 'name']);

        return response()->json($cameras);
    }
}

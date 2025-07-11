<?php

namespace App\Jobs;

use App\Models\monitoringSystem\Camera;
use App\Models\monitoringSystem\Nvr;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckDeviceStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.j
     */
    public function handle(): void
    {
        //camaras 
        $cameras = Camera::all(['mac', 'ip']);
        foreach ($cameras as $camera) {
            $cmd = "ping -n 1 -w 500 " . escapeshellarg($camera->ip) . " > nul && echo 1 || echo 0";
            $output = shell_exec($cmd);

            $status = trim($output) === '1' ? 'online' : 'offline';

            Cache::put('camera_status_' . $camera->mac, $status, 60);

            Log::debug("Caché actualizada para MAC {$camera->mac}: $status");
        }

        //nvrs 
        $nvrs = Nvr::all(['mac', 'ip']);
        foreach ($nvrs as $nvr) {
            $cmd = "ping -n 1 -w 500 " . escapeshellarg($nvr->ip) . " > nul && echo 1 || echo 0";
            $output = shell_exec($cmd);

            $status = trim($output) === '1' ? 'online' : 'offline';

            Cache::put('nvr_status_' . $nvr->mac, $status, 60);

            Log::debug("Caché actualizada para MAC {$nvr->mac}: $status");
        }
    }
}

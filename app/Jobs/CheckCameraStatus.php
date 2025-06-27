<?php

namespace App\Jobs;

use App\Models\monitoringSystem\Camera;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CheckCameraStatus implements ShouldQueue
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
     * Execute the job.
     */
    public function handle(): void
    {
        $cameras = Camera::all(['mac', 'ip']);
        foreach ($cameras as $camera) {
            $cmd = "ping -n 1 -w 1000 " . escapeshellarg($camera->ip) . " > nul && echo 1 || echo 0";
            $status = shell_exec($cmd) == 1 ? 'Activo' : 'Inactivo';
            Cache::put('camera_status_' . $camera->mac, $status, 10);
        }
    }
}

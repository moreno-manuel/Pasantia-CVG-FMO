<?php

namespace App\Jobs;

use App\Models\monitoringSystem\Camera;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
     * Execute the job.j
     */
    public function handle(): void
    {
        try {
            $cameras = Camera::all(['mac', 'ip']);

            foreach ($cameras as $camera) {
                $cmd = "ping -n 1 -w 500 " . escapeshellarg($camera->ip) . " > nul && echo 1 || echo 0";
                $output = shell_exec($cmd);

                $status = trim($output) === '1' ? 'online' : 'offline';

                Cache::put('camera_status_' . $camera->mac, $status, 60);

                Log::debug("Caché actualizada para MAC {$camera->mac}: $status");
            }
        } catch (\Exception $e) {
            Log::error("Error en CheckCameraStatus: " . $e->getMessage());
            throw $e; // Para que Laravel reintente si es necesario
        }
    }
}

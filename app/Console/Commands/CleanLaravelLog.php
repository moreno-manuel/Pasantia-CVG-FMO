<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanLaravelLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia el archivo laravel.log';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logpath = storage_path('logs/laravel.log');

        if(File::exists($logpath)){
            File::put($logpath,'');
            $this->info("El archivo Laravel.log ha sido limpiando");
        }else
        $this->info('El archivo Laravel.log no existe');
    }
}

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MonitoringSystem\CameraController;
use App\Http\Controllers\MonitoringSystem\NvrController;
use App\Http\Controllers\NetworkInfrastructure\LinkController;
use App\Http\Controllers\NetworkInfrastructure\SwitchController;
use App\Models\monitoringSystem\Hddnvr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('front.home');
})->middleware('auth')->name('home');


Route::resource('switch', SwitchController::class);
Route::resource('enlace', LinkController::class);



Route::resource('camara', CameraController::class)->except(['show', 'destroy', 'edit', 'update']);
Route::resource('nvr', NvrController::class)->except(['show', 'destroy', 'edit', 'update']);
Route::resource('nvr_hdd', Hddnvr::class)->except(['show', 'destroy', 'edit', 'update', 'index']);

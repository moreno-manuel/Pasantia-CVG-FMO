<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MonitoringSystem\CameraController;
use App\Http\Controllers\MonitoringSystem\NvrController;
use App\Http\Controllers\NetworkInfrastructure\LinkController;
use App\Http\Controllers\NetworkInfrastructure\SwitchController;
use App\Models\monitoringSystem\SlotNvr;
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


Route::resource('switch', SwitchController::class)->middleware('auth');
Route::resource('enlace', LinkController::class)->middleware('auth');



Route::resource('camara', CameraController::class)->except(['show', 'destroy', 'edit', 'update']);
Route::resource('nvr', NvrController::class);
Route::resource('nvr_hdd', SlotNvr::class)->except(['show', 'edit', 'update', 'index']);

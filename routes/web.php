<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MonitoringSystem\CameraController;
use App\Http\Controllers\MonitoringSystem\ConditionAController;
use App\Http\Controllers\MonitoringSystem\NvrController;
use App\Http\Controllers\NetworkInfrastructure\LinkController;
use App\Http\Controllers\NetworkInfrastructure\SwitchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('front.home');
})->middleware('auth')->name('home');


Route::resource('switch', SwitchController::class)->middleware('auth');
Route::resource('enlace', LinkController::class)->middleware('auth');
Route::resource('nvr', NvrController::class)->middleware('auth');
Route::resource('camara', CameraController::class)->middleware('auth');
Route::resource('atencion', ConditionAController::class)->middleware('auth');

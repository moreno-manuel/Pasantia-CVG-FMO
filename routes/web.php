<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Auth\QuestionsSecurityController;
use App\Http\Controllers\Auth\RecoveryUserController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\EquipmentDisuse\EquipmentDisuseController;
use App\Http\Controllers\MonitoringSystem\CameraController;
use App\Http\Controllers\MonitoringSystem\CheckStatusController;
use App\Http\Controllers\MonitoringSystem\ConditionAController;
use App\Http\Controllers\MonitoringSystem\NvrController;
use App\Http\Controllers\NetworkInfrastructure\CameraInventoriesController;
use App\Http\Controllers\NetworkInfrastructure\LinkController;
use App\Http\Controllers\NetworkInfrastructure\SwitchController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\ReportlAccesMiddleware;
use App\Http\Middleware\UsersAccesMiddleware;
use Illuminate\Support\Facades\Route;



Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::controller(RecoveryUserController::class)
    ->prefix('recovery')
    ->group(function () {
        Route::get('searchUser', 'showStep1')->name('recovery.showStep1');
        Route::post('searchUserStore', 'storeStep1')->name('recovery.storeStep1');
        Route::get('questions', 'showStep2')->name('recovery.showStep2');
        Route::post('questionsStore', 'storeStep2')->name('recovery.storeStep2');
        Route::get('password', 'showStep3')->name('recovery.showStep3');
        Route::post('passwordStore', 'storeStep3')->name('recovery.storeStep3');
    });



Route::controller(CheckStatusController::class) //monitoreo de camaras y nvr
    ->prefix('/home')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'home')->name('home');
        Route::get('check', 'checkStatus')->name('test.check');
    });
Route::resource('switch', SwitchController::class)->middleware('auth');
Route::resource('enlace', LinkController::class)->middleware('auth');
Route::resource('nvr', NvrController::class)->middleware('auth');
Route::resource('camara', CameraController::class)->middleware('auth');
Route::controller(CameraInventoriesController::class)
    ->prefix('camara-stock')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('inventories.index');
        Route::get('crear', 'create')->name('inventories.create');
        Route::post('', 'store')->name('inventories.store');
        Route::delete('{id}', 'destroy')->name('inventories.destroy');
    });
Route::resource('atencion', ConditionAController::class)->middleware('auth');


Route::controller(EquipmentDisuseController::class) //equipos eliminados
    ->prefix('historial-eliminados')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('eliminated.index');
        Route::get('{id}', 'show')->name('eliminated.show');
        Route::delete('{id}', 'destroy')->name('eliminated.destroy');
    });

Route::controller(ReportController::class) //para exportar los reportes 
    ->prefix('reporte')
    ->middleware(ReportlAccesMiddleware::class)
    ->group(function () {
        Route::get('', 'index')->name('report.index');
        Route::get('switch', 'exportSwitch')->name('export.switch');
        Route::get('link', 'exportLink')->name('export.link');
        Route::get('camera-stock', 'exportCameraStock')->name('export.cameraStock');
        Route::get('nvr', 'exportNvr')->name('export.nvr');
        Route::get('camera', 'exportCamera')->name('export.camera');
        Route::get('disuse', 'exportEquipmentDisuse')->name('export.EquipmentDisuse');
        Route::get('report', 'exportReport')->name('export.report');
    });


Route::controller(PerfilController::class) //perfil del usuario logueado
    ->prefix('perfil')
    ->middleware('auth')
    ->group(function () {
        Route::get('{user}', 'edit')->name('perfil.edit');
        Route::put('{user}', 'update')->name('perfil.update');
    });

Route::controller(QuestionsSecurityController::class) // preguntas de seguridad para el usuario logueado
    ->prefix('preguntas-seguridad')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'showForm')->name('security.showForm');
        Route::post('', 'store')->name('security.store');
        Route::put('{user}', 'update')->name('security.update');
    });

Route::resource('users', UserController::class)->middleware(UsersAccesMiddleware::class)->except('show'); //para el control de los usuarios registrados  

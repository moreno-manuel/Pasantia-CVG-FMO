<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Auth\QuestionsSecurityController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\EquipmentDisuse\EquipmentDisuseController;
use App\Http\Controllers\MonitoringSystem\CameraController;
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

Route::get('/home', function () {
    return view('front.home');
})->middleware('auth')->name('home');


Route::resource('switch', SwitchController::class)->middleware('auth');
Route::resource('enlace', LinkController::class)->middleware('auth');
Route::resource('nvr', NvrController::class)->middleware('auth');
Route::resource('camara', CameraController::class)->middleware('auth');
Route::controller(CameraInventoriesController::class)
    ->prefix('inventario/camara')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('inventories.index');
        Route::get('crear', 'create')->name('inventories.create');
        Route::post('', 'store')->name('inventories.store');
        Route::delete('{id}', 'destroy')->name('inventories.destroy');
    });
Route::resource('atencion', ConditionAController::class)->middleware('auth');


Route::controller(EquipmentDisuseController::class) //equipos eliminados
    ->prefix('historial/eliminados')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('eliminated.index');
        Route::get('{id}', 'show')->name('eliminated.show');
        Route::delete('{id}', 'destroy')->name('eliminated.destroy');
    });


Route::controller(ReportController::class) //reportes
    ->prefix('report')
    ->middleware(ReportlAccesMiddleware::class)
    ->group(function () {
        Route::get('', 'index')->name('report.index');
        Route::get('export/switch', 'exportSwitch')->name('report.switch');
        Route::get('export/link', 'exportLink')->name('report.link');
        Route::get('export/camera-stock', 'exportCameraStock')->name('report.cameraStock');
        Route::get('export/camera-by-nvr', 'exportCameraByNvr')->name('report.cameraByNvr');
        Route::get('export/report', 'exporReport')->name('report.report');
    });


Route::controller(PerfilController::class) //perfil del usuario logueado
    ->prefix('perfil')
    ->middleware('auth')
    ->group(function () {
        Route::get('{user}', 'edit')->name('perfil.edit');
        Route::put('{user}', 'update')->name('perfil.update');
    });

Route::controller(QuestionsSecurityController::class) // preguntas de seguridad para el usuario logueado
    ->prefix('security')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'showForm')->name('security.showForm');
        Route::post('', 'store')->name('security.store');
        Route::put('{user}', 'update')->name('security.update');
    });

Route::resource('users', UserController::class)->middleware(UsersAccesMiddleware::class)->except('show'); //para el control de los usuarios registrados  

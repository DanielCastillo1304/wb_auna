<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BusinessUnitController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DiskCapacityController;
use App\Http\Controllers\RamCapacityController;
use App\Http\Controllers\ReturnReasonController;
use App\Http\Controllers\UserController;

Route::fallback(function () {
    return redirect('/'); // Redirige al inicio si la ruta no existe
});

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'login'])->name('login.post');
});

Route::prefix('register')->group(function () {
    Route::get('/{section?}', [RegisterController::class, 'index'])->name('register');
    Route::post('/send_code', [RegisterController::class, 'send_code'])->name('register.send_code');
    Route::post('/store/{section?}', [RegisterController::class, 'store'])->name('register.store');
    Route::post('/', [RegisterController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/list', [UserController::class, 'index'])->name('list');
    Route::get('/password', [UserController::class, 'password'])->name('password');
    Route::post('/change_password/{id?}', [UserController::class, 'change_password'])->name('change_password');
    Route::post('/reset/{id?}', [UserController::class, 'reset'])->name('reset');
    Route::get('/form/{id?}', [UserController::class, 'form'])->name('form');
    Route::post('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/store/{id?}', [UserController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [UserController::class, 'records'])->name('records');
    Route::post('/search', [UserController::class, 'search'])->name('search');
    Route::get('/show/{id}', [UserController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('equipment_type')->name('equipment_type.')->group(function () {
    Route::get('/list', [EquipmentTypeController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [EquipmentTypeController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [EquipmentTypeController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [EquipmentTypeController::class, 'records'])->name('records');
    Route::post('/search', [EquipmentTypeController::class, 'search'])->name('search');
    Route::get('/show/{id}', [EquipmentTypeController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [EquipmentTypeController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('business_unit')->name('business_unit.')->group(function () {
    Route::get('/list', [BusinessUnitController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [BusinessUnitController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [BusinessUnitController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [BusinessUnitController::class, 'records'])->name('records');
    Route::post('/search', [BusinessUnitController::class, 'search'])->name('search');
    Route::get('/show/{id}', [BusinessUnitController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [BusinessUnitController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('location')->name('location.')->group(function () {
    Route::get('/list', [LocationController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [LocationController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [LocationController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [LocationController::class, 'records'])->name('records');
    Route::post('/search', [LocationController::class, 'search'])->name('search');
    Route::get('/show/{id}', [LocationController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [LocationController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('ram_capacity')->name('ram_capacity.')->group(function () {
    Route::get('/list', [RamCapacityController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [RamCapacityController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [RamCapacityController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [RamCapacityController::class, 'records'])->name('records');
    Route::post('/search', [RamCapacityController::class, 'search'])->name('search');
    Route::get('/show/{id}', [RamCapacityController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [RamCapacityController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('disk_capacity')->name('disk_capacity.')->group(function () {
    Route::get('/list', [DiskCapacityController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [DiskCapacityController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [DiskCapacityController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [DiskCapacityController::class, 'records'])->name('records');
    Route::post('/search', [DiskCapacityController::class, 'search'])->name('search');
    Route::get('/show/{id}', [DiskCapacityController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [DiskCapacityController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('return_reason')->name('return_reason.')->group(function () {
    Route::get('/list', [ReturnReasonController::class, 'index'])->name('list');
    Route::get('/form/{id?}', [ReturnReasonController::class, 'form'])->name('form');
    Route::post('/store/{id?}', [ReturnReasonController::class, 'store'])->name('store');
    Route::get('/records/{from}/{to}/{keyword?}', [ReturnReasonController::class, 'records'])->name('records');
    Route::post('/search', [ReturnReasonController::class, 'search'])->name('search');
    Route::get('/show/{id}', [ReturnReasonController::class, 'show'])->name('show');
    Route::delete('/destroy/{id}', [ReturnReasonController::class, 'destroy'])->name('destroy');
});
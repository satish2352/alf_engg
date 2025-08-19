<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadm\LoginController;
use App\Http\Controllers\Superadm\DashboardController;
use App\Http\Controllers\Superadm\RoleController;
use App\Http\Controllers\Superadm\DesignationsController;
use App\Http\Controllers\Superadm\PlantMasterController;

Route::get('login', function () {
    return view('superadm.login');
});

// Route::get('super', [LoginController::class, 'loginsuper'])->name('super');
Route::post('superlogin', [LoginController::class, 'validateSuperLogin'])->name('superlogin');
Route::group(['middleware' => ['SuperAdmin']], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role management routes
    Route::get('/roles/list', [RoleController::class, 'index'])->name('roles.list');
    Route::get('/roles/add', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/add', [RoleController::class, 'save'])->name('roles.save');
    Route::get('/roles/edit/{encodedId}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/update/{encodedId}', [RoleController::class, 'update'])->name('roles.update');
    Route::post('/roles/delete', [RoleController::class, 'delete'])->name('roles.delete');
    Route::post('/roles/update-status', [RoleController::class, 'updateStatus'])->name('roles.updatestatus');



    // Role management routes
    Route::get('/designations/list', [DesignationsController::class, 'index'])->name('designations.list');
    Route::get('/designations/add', [DesignationsController::class, 'create'])->name('designations.create');
    Route::post('/designations/add', [DesignationsController::class, 'save'])->name('designations.save');
    Route::get('/designations/edit/{encodedId}', [DesignationsController::class, 'edit'])->name('designations.edit');
    Route::post('/designations/update/{encodedId}', [DesignationsController::class, 'update'])->name('designations.update');
    Route::post('/designations/delete', [DesignationsController::class, 'delete'])->name('designations.delete');
    Route::post('/designations/update-status', [DesignationsController::class, 'updateStatus'])->name('designations.updatestatus');


    
    // plantmaster management routes
    Route::get('/plantmaster/list', [PlantMasterController::class, 'index'])->name('plantmaster.list');
    Route::get('/plantmaster/add', [PlantMasterController::class, 'create'])->name('plantmaster.create');
    Route::post('/plantmaster/add', [PlantMasterController::class, 'save'])->name('plantmaster.save');
    Route::get('/plantmaster/edit/{encodedId}', [PlantMasterController::class, 'edit'])->name('plantmaster.edit');
    Route::post('/plantmaster/update/{encodedId}', [PlantMasterController::class, 'update'])->name('plantmaster.update');
    Route::post('/plantmaster/delete', [PlantMasterController::class, 'delete'])->name('plantmaster.delete');
    Route::post('/plantmaster/update-status', [PlantMasterController::class, 'updateStatus'])->name('plantmaster.updatestatus');




    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});

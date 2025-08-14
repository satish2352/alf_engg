<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadm\LoginController;
use App\Http\Controllers\Superadm\Dashboard\DashboardController;
use App\Http\Controllers\Superadm\Role\RoleController;

Route::get('login', function () {
    return view('superadm.login');
});

// Route::get('super', [LoginController::class, 'loginsuper'])->name('super');
Route::post('superlogin', [LoginController::class, 'validateSuperLogin'])->name('superlogin');
Route::group(['middleware' => ['SuperAdmin']], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::get('/roles/list', [RoleController::class, 'index'])->name('roles.list');
    // Route::get('/roles/add', [RoleController::class, 'create'])->name('roles.create');
    // Route::get('/roles/add', [RoleController::class, 'save'])->name('roles.save');
    // Route::post('addrole', [RoleController::class, 'save'])->name('addrole');
    // Route::post('updaterole', [RoleController::class, 'update'])->name('updaterole');
    // Route::post('deleterole', [RoleController::class, 'delete'])->name('deleterole');


     // Role management routes
    Route::get('/roles/list', [RoleController::class, 'index'])->name('roles.list');

    // Add Role
    Route::get('/roles/add', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/add', [RoleController::class, 'save'])->name('roles.save');

    // Edit Role
    Route::get('/roles/edit/{encodedId}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/update/{encodedId}', [RoleController::class, 'update'])->name('roles.update');

    // Delete Role
    Route::post('/roles/delete', [RoleController::class, 'delete'])->name('roles.delete');

    


    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});

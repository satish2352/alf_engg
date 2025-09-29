<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadm\LoginController;
use App\Http\Controllers\Superadm\DashboardController;
use App\Http\Controllers\EmpDashboardController;
use App\Http\Controllers\Superadm\RoleController;
use App\Http\Controllers\Superadm\DesignationsController;
use App\Http\Controllers\Superadm\PlantMasterController;
use App\Http\Controllers\Superadm\ProjectsController;
use App\Http\Controllers\Superadm\DepartmentsController;
use App\Http\Controllers\Superadm\EmployeesController;
use App\Http\Controllers\Superadm\ChangePasswordController;
use App\Http\Controllers\Superadm\EmployeeTypeController;





Route::get('login', [LoginController::class, 'loginsuper'])->name('login');
Route::post('superlogin', [LoginController::class, 'validateSuperLogin'])->name('superlogin');

Route::group(['middleware' => ['SuperAdmin']], function () {        

  

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    Route::post('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('update-password');
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





    // ProjectsController management routes
    Route::get('/projects/list', [ProjectsController::class, 'index'])->name('projects.list');
    Route::get('/projects/add', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/projects/add', [ProjectsController::class, 'save'])->name('projects.save');
    Route::get('/projects/edit/{encodedId}', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::post('/projects/update/{encodedId}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::post('/projects/delete', [ProjectsController::class, 'delete'])->name('projects.delete');
    Route::post('/projects/update-status', [ProjectsController::class, 'updateStatus'])->name('projects.updatestatus');
    Route::post('/projects/list-ajax', [ProjectsController::class, 'listajaxlist'])->name('projects.list-ajax');



    // departments management routes
    Route::get('/departments/list', [DepartmentsController::class, 'index'])->name('departments.list');
    Route::get('/departments/add', [DepartmentsController::class, 'create'])->name('departments.create');
    Route::post('/departments/add', [DepartmentsController::class, 'save'])->name('departments.save');
    Route::get('/departments/edit/{encodedId}', [DepartmentsController::class, 'edit'])->name('departments.edit');
    Route::post('/departments/update/{encodedId}', [DepartmentsController::class, 'update'])->name('departments.update');
    Route::post('/departments/delete', [DepartmentsController::class, 'delete'])->name('departments.delete');
    Route::post('/departments/update-status', [DepartmentsController::class, 'updateStatus'])->name('departments.updatestatus');
    Route::post('/departments/list-ajax', [DepartmentsController::class, 'listajaxlist'])->name('departments.list-ajax');


    // employees management routes
    Route::get('/employees/list', [EmployeesController::class, 'index'])->name('employees.list');
    Route::get('/employees/ajax-list', [EmployeesController::class, 'ajaxList'])->name('employees.ajax'); 
    Route::get('/employees/add', [EmployeesController::class, 'create'])->name('employees.create');
    Route::post('/employees/add', [EmployeesController::class, 'save'])->name('employees.save');
    Route::get('/employees/edit/{encodedId}', [EmployeesController::class, 'edit'])->name('employees.edit');
    Route::PUT('/employees/update/{encodedId}', [EmployeesController::class, 'update'])->name('employees.update');
    Route::post('/employees/delete', [EmployeesController::class, 'delete'])->name('employees.delete');
    Route::post('/employees/update-status', [EmployeesController::class, 'updateStatus'])->name('employees.updatestatus');
    Route::post('/employees/list-ajax', [EmployeesController::class, 'listajaxlist'])->name('employees.list-ajax');
    Route::post('/employees/update-status', [EmployeesController::class, 'updateStatus'])->name('employees.updatestatus');

    // employees Type
    Route::get('/employee-types/list', [EmployeeTypeController::class, 'index'])->name('employee-types.list');
    Route::get('/employee-types/add', [EmployeeTypeController::class, 'create'])->name('employee-types.create');
    Route::post('/employee-types/add', [EmployeeTypeController::class, 'save'])->name('employee-types.save');
    Route::get('/employee-types/edit/{encodedId}', [EmployeeTypeController::class, 'edit'])->name('employee-types.edit');
    Route::post('/employee-types/update/{encodedId}', [EmployeeTypeController::class, 'update'])->name('employee-types.update');
    Route::post('/employee-types/delete', [EmployeeTypeController::class, 'delete'])->name('employee-types.delete');
    Route::post('/employee-types/update-status', [EmployeeTypeController::class, 'updateStatus'])->name('employee-types.updatestatus');


    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});


Route::group(['middleware' => ['Employee']], function () {

    Route::get('dashboard-emp', [EmpDashboardController::class, 'index'])->name('dashboard-emp');
    Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    Route::post('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('update-password');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

});
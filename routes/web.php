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
use App\Http\Controllers\Superadm\EmployeePlantAssignmentController;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\EmployeePlantAssignment;
use App\Models\PlantMasters;
use App\Http\Controllers\Superadm\EmployeeLoginController;
use App\Http\Controllers\Superadm\FinancialYearController;


Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    return "<h3>✅ All Laravel caches cleared successfully!</h3>";
})->name('clear.cache');


Route::get('login', [LoginController::class, 'loginsuper'])->name('login');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'loginsuper'])->name('login');
    Route::post('superlogin', [LoginController::class, 'validateSuperLogin'])->name('superlogin');

    Route::get('emp-login', [EmployeeLoginController::class, 'loginEmployee'])->name('emp.login');
    Route::post('emp-login', [EmployeeLoginController::class, 'validateEmpLogin'])->name('emp.login.submit');
});

Route::post('superlogin', [LoginController::class, 'validateSuperLogin'])->name('superlogin');

Route::get('emp-login', [EmployeeLoginController::class, 'loginEmployee'])->name('emp.login');
Route::post('emp-login', [EmployeeLoginController::class, 'validateEmpLogin'])->name('emp.login.submit');
Route::get('emp-logout', [EmployeeLoginController::class, 'logOut'])->name('emp.logout');

Route::get('/get-plants-by-email', function (Request $req) {
    $emp = Employees::where('employee_user_name', $req->email)->first();

    if (!$emp) return response()->json([]);

    $plants = EmployeePlantAssignment::where('employee_id', $emp->id)
        ->where('is_active', 1)
        ->where('is_deleted', 0)
        ->pluck('plant_id');

    return PlantMasters::whereIn('id', $plants)->get(['id', 'plant_name', 'plant_code']);

});

Route::group(['middleware' => ['SuperAdmin']], function () {        

  

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    // Route::post('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('update-password');
    Route::get('/admin/change-password', [ChangePasswordController::class, 'index'])->name('admin.change-password');
    Route::post('/admin/update-password', [ChangePasswordController::class, 'updatePassword'])->name('admin.update-password');
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

    Route::get('/plantmaster/export', [PlantMasterController::class, 'export'])->name('plantmaster.export');






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


    Route::get('/departments/export', [DepartmentsController::class, 'export'])->name('departments.export');


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

    Route::get('employees/export', [EmployeesController::class, 'export'])->name('employees.export');


    // Financial Year routes
    Route::get('/financial-year/list', [FinancialYearController::class, 'index'])->name('financial-year.list');
    Route::get('/financial-year/add', [FinancialYearController::class, 'create'])->name('financial-year.create');
    Route::post('/financial-year/add', [FinancialYearController::class, 'save'])->name('financial-year.save');
    Route::get('/financial-year/edit/{encodedId}', [FinancialYearController::class, 'edit'])->name('financial-year.edit');
    Route::post('/financial-year/update/{encodedId}', [FinancialYearController::class, 'update'])->name('financial-year.update');
    Route::post('/financial-year/delete', [FinancialYearController::class, 'delete'])->name('financial-year.delete');
    Route::post('/financial-year/update-status', [FinancialYearController::class, 'updateStatus'])->name('financial-year.updatestatus');

    Route::post('employee/assignments/send-api', [EmployeePlantAssignmentController::class, 'sendApi'])->name('employee.assignments.sendApi');


    Route::get('/employee-assignments/list', [EmployeePlantAssignmentController::class, 'index'])
        ->name('employee.assignments.list');

    Route::get('/employee-assignments/add', [EmployeePlantAssignmentController::class, 'create'])
        ->name('employee.assignments.create');

    Route::post('/employee-assignments/add', [EmployeePlantAssignmentController::class, 'save'])
        ->name('employee.assignments.save');

    Route::get('/employee-assignments/edit/{encodedId}', [EmployeePlantAssignmentController::class, 'edit'])
        ->name('employee.assignments.edit');

    Route::post('/employee-assignments/update/{encodedId}', [EmployeePlantAssignmentController::class, 'update'])
        ->name('employee.assignments.update');

    Route::post('/employee-assignments/delete', [EmployeePlantAssignmentController::class, 'delete'])
        ->name('employee.assignments.delete');

    Route::post('/employee-assignments/update-status', [EmployeePlantAssignmentController::class, 'updateStatus'])
        ->name('employee.assignments.updatestatus');

    Route::get('/employee-assignments/export', [EmployeePlantAssignmentController::class, 'export'])
    ->name('employee.assignments.export');

    Route::post('/employee/assignments/get-projects', [EmployeePlantAssignmentController::class, 'getProjects'])
    ->name('employee.assignments.getProjects');

    Route::get('/employee/assignments/checkSendApi', [EmployeePlantAssignmentController::class, 'checkSendApi'])
     ->name('employee.assignments.checkSendApi');



    // Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('admin/logout', [LoginController::class, 'logOut'])->name('admin.logout');

});


// Route::group(['middleware' => ['Employee']], function () {

//     Route::get('dashboard-emp', [EmpDashboardController::class, 'index'])->name('dashboard-emp');
//     Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
//     Route::post('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('update-password');
//     Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// });

Route::group(['middleware' => ['Employee']], function () {
    Route::get('dashboard-emp', [EmpDashboardController::class, 'index'])->name('dashboard-emp');
    // Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
    // Route::post('/update-password', [ChangePasswordController::class, 'updatePassword'])->name('update-password');
    Route::get('/employee/change-password', [ChangePasswordController::class, 'index'])->name('employee.change-password');
    Route::post('/employee/update-password', [ChangePasswordController::class, 'updatePassword'])->name('employee.update-password');
    // Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('emp/logout', [EmployeeLoginController::class, 'logOut'])->name('emp.logout');
});

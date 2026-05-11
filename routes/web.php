<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    BranchController,
    CompanyController,
    DepartmentController,
    EmployeeController,
    InsuranceController,
    LoginController,
    PermissionController,
    PositionController,
    RoleController,
    RolePermissionController,
    UserController,
    UserRoleController,
    VehicleController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['web'])->name('web.')->group(function () {
    // Login Routes
    Route::get('/signin', [LoginController::class, 'signin'])->name('signin');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/save-web-token-session', [LoginController::class, 'saveWebTokenSession']);

    Route::middleware(['check.session'])->group(function () {
        // DataTables routes
        Route::get('/', [LoginController::class, 'index'])->name('index');
        Route::get('/position/data', [PositionController::class, 'data'])->name('position.data')->middleware('permission:position');
        Route::get('/insurance/data', [InsuranceController::class, 'data'])->name('insurance.data')->middleware('permission:insurance');
        Route::get('/company/data', [CompanyController::class, 'data'])->name('company.data')->middleware('permission:company');
        Route::get('/employee/data', [EmployeeController::class, 'data'])->name('employee.data')->middleware('permission:employee');
        Route::get('/vehicle/data', [VehicleController::class, 'data'])->name('vehicle.data')->middleware('permission:vehicle');
        Route::get('/department/data', [DepartmentController::class, 'data'])->name('department.data')->middleware('permission:department');
        Route::get('/branch/data', [BranchController::class, 'data'])->name('branch.data')->middleware('permission:branch');
        // End DataTables

        // Custom Routes
        Route::post('/employee/upload', [EmployeeController::class, 'upload'])->name('employee.upload');
        // End Custom Routes

        // Resources
        Route::resource('branch', BranchController::class)->middleware('permission:branch');
        Route::resource('company', CompanyController::class)->middleware('permission:company');
        Route::resource('department', DepartmentController::class)->middleware('permission:department');
        Route::resource('employee', EmployeeController::class)->middleware('permission:employee');
        Route::resource('insurance', InsuranceController::class)->middleware('permission:insurance');
        Route::resource('position', PositionController::class)->middleware('permission:position');
        Route::resource('vehicle', VehicleController::class)->middleware('permission:vehicle');
        Route::resource('user', UserController::class);
        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);
        Route::resource('role-permission', RolePermissionController::class);
        Route::resource('user-role', UserRoleController::class);
        // End Resources

    });
});

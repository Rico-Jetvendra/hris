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
    Route::get('/', [LoginController::class, 'index'])->name('index');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    Route::middleware(['check.session'])->name('web.')->group(function () {
        // DataTables routes
        Route::get('/position/data', [PositionController::class, 'data'])->name('position.data');
        Route::get('/insurance/data', [InsuranceController::class, 'data'])->name('insurance.data');
        Route::get('/company/data', [CompanyController::class, 'data'])->name('company.data');
        Route::get('/employee/data', [EmployeeController::class, 'data'])->name('employee.data');
        Route::get('/vehicle/data', [VehicleController::class, 'data'])->name('vehicle.data');
        Route::get('/department/data', [DepartmentController::class, 'data'])->name('department.data');
        Route::get('/branch/data', [BranchController::class, 'data'])->name('branch.data');
        // End DataTables

        // Custom Routes
        Route::post('/employee/upload', [EmployeeController::class, 'upload'])->name('employee.upload');
        // End Custom Routes

        // Resources
        Route::resource('branch', BranchController::class);
        Route::resource('company', CompanyController::class);
        Route::resource('department', DepartmentController::class);
        Route::resource('employee', EmployeeController::class);
        Route::resource('insurance', InsuranceController::class);
        Route::resource('position', PositionController::class);
        Route::resource('vehicle', VehicleController::class);
        Route::resource('user', UserController::class);
        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);
        Route::resource('role-permission', RolePermissionController::class);
        Route::resource('user-role', UserRoleController::class);
        // End Resources

    });
});

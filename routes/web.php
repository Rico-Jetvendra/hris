<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    BranchController,
    CallController,
    CompanyController,
    DepartmentController,
    EmployeeController,
    InsuranceController,
    LoginController,
    NotificationController,
    PositionController,
    VehicleController,
    VehicleAssignmentController,
    VisitController,
};

Route::middleware(['web'])->name('web.')->group(function () {
    // Login Routes
    Route::get('/signin', [LoginController::class, 'signin'])->name('signin');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/save-web-token-session', [LoginController::class, 'saveWebTokenSession']);

    Route::middleware(['check.session'])->group(function () {
        // DataTables routes
        Route::get('/', [LoginController::class, 'index'])->name('index');
        Route::get('/position/data', [PositionController::class, 'data'])->name('position.data')->middleware('permission:master.position');
        Route::get('/insurance/data', [InsuranceController::class, 'data'])->name('insurance.data')->middleware('permission:master.insurance');
        Route::get('/company/data', [CompanyController::class, 'data'])->name('company.data')->middleware('permission:master.company');
        Route::get('/employee/data', [EmployeeController::class, 'data'])->name('employee.data')->middleware('permission:master.employee');
        Route::get('/vehicle/data', [VehicleController::class, 'data'])->name('vehicle.data')->middleware('permission:master.vehicle');
        Route::get('/department/data', [DepartmentController::class, 'data'])->name('department.data')->middleware('permission:master.department');
        Route::get('/branch/data', [BranchController::class, 'data'])->name('branch.data')->middleware('permission:master.branch');
        Route::get('/vehicle-assignment/data', [VehicleAssignmentController::class, 'data'])->name('vehicle-assignment.data')->middleware('permission:management.vehicle_assignment');
        Route::get('/call/data', [CallController::class, 'data'])->name('call.data')->middleware('permission:management.call');
        Route::get('/visit/data', [VisitController::class, 'data'])->name('visit.data')->middleware('permission:management.visit');
        // End DataTables

        // Custom Routes
        Route::post('/employee/upload', [EmployeeController::class, 'upload'])->name('employee.upload');
        Route::post('/vehicle/upload', [VehicleController::class, 'upload'])->name('vehicle.upload');

        Route::post('/call/comment', [CallController::class, 'storeComment'])->name('call.comment.store');
        Route::post('/call/replies', [CallController::class, 'storeReplies'])->name('call.replies.store');
        Route::delete('/call/comment/{id}', [CallController::class, 'deleteComment'])->name('call.comment.delete');

        Route::post('/visit/comment', [VisitController::class, 'storeComment'])->name('visit.comment.store');
        Route::post('/visit/replies', [VisitController::class, 'storeReplies'])->name('visit.replies.store');
        Route::delete('/visit/comment/{id}', [VisitController::class, 'deleteComment'])->name('visit.comment.delete');

        Route::post('/due-email', [NotificationController::class, 'sendDueEmail'])->name('due.email');
        Route::delete('/destroy-image/{id}', [VehicleController::class, 'destroyImage'])->name('vehicle.destroyImage');
        // End Custom Routes

        // Resources
        Route::resource('branch', BranchController::class)->middleware('permission:master.branch');
        Route::resource('company', CompanyController::class)->middleware('permission:master.company');
        Route::resource('department', DepartmentController::class)->middleware('permission:master.department');
        Route::resource('employee', EmployeeController::class)->middleware('permission:master.employee');
        Route::resource('insurance', InsuranceController::class)->middleware('permission:master.insurance');
        Route::resource('position', PositionController::class)->middleware('permission:master.position');
        Route::resource('vehicle', VehicleController::class)->middleware('permission:master.vehicle');
        Route::resource('vehicle-assignment', VehicleAssignmentController::class)->middleware('permission:management.vehicle_assignment');
        Route::resource('call', CallController::class)->middleware('permission:management.call');
        Route::resource('visit', VisitController::class)->middleware('permission:management.visit');
        // End Resources

    });
});

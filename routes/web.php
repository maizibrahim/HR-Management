<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Employee\CountryController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\SupervisorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

//Employee controller
use App\Http\Controllers\Employee\DesignationController;
use App\Http\Controllers\Employee\EmployeeRegController;


use App\Http\Controllers\Leave\LeaveGroupController;
use App\Http\Controllers\Leave\LeaveTypeController;
use App\Http\Controllers\Leave\LeaveRequestController;
use App\Http\Controllers\Leave\UserLeaveController;
use App\Http\Controllers\Leave\DailyLeaveRecordsController;


Route::get('/', function () {
    return view('/auth/login');
    });

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin All Route
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('profile.logout');

});
// user Control Route
Route::controller(UserController::class)->group(function () {
    Route::get('/user/profile', 'Profile')->name('user.profile');
    Route::get('/user/edit/profile', 'EditProfile')->name('user.edit');
    Route::post('/user/store/profile', 'StoreProfile')->name('user.store');

    Route::get('/user/change/password', 'ChangePassword')->name('change.password');
    Route::post('/user/store/password', 'UpdatePassword')->name('update.password');

});
//Supervisor Route
Route::controller(SupervisorController::class)->group(function () {
    Route::get('/employee/supervisor/all', 'AllSupervisor')->name('supervisor.all');
    Route::get('/employee/supervisor/new', 'AddSupervisor')->name('supervisor.add');
    Route::post('/employee/supervisor/store', 'StoreSupervisor')->name('supervisor.store');
    Route::get('/employee/supervisor/delete/{id}','DeleteSupervisor')->name('supervisor.delete');
});

//Permission All route
Route::controller(RoleController::class)->group(function () {
    Route::get('/all/permission', 'AllPermission')->name('permission.all');
    Route::get('/new/permission', 'AddPermission')->name('permission.add');
    Route::post('/store/permission', 'StorePermission')->name('permission.store');
    Route::get('/edit/permission/{id}', 'EditPermission')->name('permission.edit');
    Route::post('/update/permission', 'UpdatePermission')->name('permission.update');
    Route::get('/delete/permission/{id}','DeletePermission')->name('permission.delete');
});

//Role and Permission All route
Route::controller(RoleController::class)->group(function () {
    Route::get('/all/role', 'AllRole')->name('roles.all');
    Route::get('/new/role', 'AddRole')->name('roles.add');
    Route::post('/store/role', 'StoreRole')->name('roles.store');
    Route::get('/edit/role/{id}', 'EditRole')->name('roles.edit');
    Route::post('/update/role/', 'UpdateRole')->name('roles.update');
    Route::get('/delete/role/{id}','DeleteRole')->name('roles.delete');

    Route::get('/all/role/permission','AllRolesPermission')->name('role.permission.all');
    Route::get('/add/role/permission','AddRolesPermission')->name('role.permission.add');
    Route::post('/role/permission/store','RolePermissionStore')->name('role.permission.store');
    Route::get('/role/permission/edit/{id}','RolePermissionEdit')->name('role.permission.edit');
    Route::post('/role/permission/update/{id}','RolePermissionUpdate')->name('role.permission.update');
    Route::get('/role/permission/delete/{id}','RolePermissionDelete')->name('role.permission.delete');

});

//Permission All route
Route::controller(AdminController::class)->group(function () {
   Route::get('/all/user', 'AllUser')->name('user.all');
   Route::get('/new/user', 'AddUser')->name('user.add');
   Route::post('/store/user', 'StoreUser')->name('users.store');
   Route::get('/edit/user/{id}', 'EditUser')->name('users.pemission.edit');
   Route::post('/update/user/{id}', 'UpdateUser')->name('user.update');
   Route::get('/delete/user/{id}','DeleteUser')->name('user.delete');
});

//Designation All route
Route::controller(DesignationController::class)->group(function () {
    Route::get('/all/classification', 'AllClassification')->name('classification.all');
    Route::get('/new/classification', 'AddClassification')->name('classification.add');
    Route::post('/store/classification', 'StoreClassification')->name('classification.store');
    Route::get('/edit/classification/{id}', 'EditClassification')->name('classification.edit');
    Route::post('/update/classification/{id}', 'UpdateClassification')->name('classification.update');
   Route::get('/delete/classification/{id}','DeleteClassification')->name('classification.delete');

    Route::get('/all/rank', 'AllRank')->name('rank.all');
    Route::get('/new/rank', 'AddRank')->name('rank.add');
   Route::post('/store/rank', 'StoreRank')->name('rank.store');
   Route::get('/edit/rank/{id}', 'EditRank')->name('rank.edit');
   Route::post('/update/rank/{id}', 'UpdateRank')->name('rank.update');
   Route::get('/delete/rank/{id}','DeleteRank')->name('rank.delete');

});

//Country All route
Route::controller(CountryController::class)->group(function () {
    Route::get('/all/country', 'AllCountry')->name('country.all');
    Route::get('/new/country', 'AddCountry')->name('country.add');
    Route::post('/store/country', 'StoreCountry')->name('country.store');
   Route::get('/edit/country/{id}', 'EditCountry')->name('country.edit');
   Route::post('/update/country/{id}', 'UpdateCountry')->name('country.update');
   Route::get('/delete/country/{id}','DeleteCountry')->name('country.delete');
});


//Employee All route
Route::controller(EmployeeRegController::class)->group(function () {
   Route::get('/all/employee', 'AllEmployee')->name('employee.all');
   Route::get('/new/employee', 'AddEmployee')->name('employee.add');
   Route::post('/store/employee', 'StoreEmployee')->name('employee.store');
   Route::get('/edit/employee/{id}', 'EditEmployee')->name('employee.edit');
   Route::post('/update/employee/{id}', 'UpdateEmployee')->name('employee.update');
   Route::get('/delete/employee/{id}','DeleteEmployee')->name('employee.delete');
   Route::get('/detail/employee/{id}','DetailEmployee')->name('employee.detail');
});

//Employee salary All route
Route::controller(SalaryController::class)->group(function () {
    Route::get('/all/employee/salary', 'AllSalary')->name('salary.all');
    Route::get('/new/employee/salary', 'AddSalary')->name('salary.add');
    Route::post('/store/employee/salary', 'StoreSalary')->name('salary.store');
    Route::get('/edit/employee/salary/{id}', 'EditSalary')->name('salary.edit');
    Route::post('/update/employee/salary/{id}', 'UpdateSalary')->name('salary.update');
    Route::get('/delete/employee/salary/{id}','DeleteSalary')->name('salary.delete');
 });

// User Management Routes
    Route::resource('users', EmployeeRegController::class);
    Route::get('/users/{user}/assign-supervisor', [EmployeeRegController::class, 'assignSupervisor'])->name('users.assign-supervisor');
    Route::patch('/users/{user}/update-supervisor', [EmployeeRegController::class, 'updateSupervisor'])->name('users.update-supervisor');
    Route::get('/users/{user}/assign-leave-group', [EmployeeRegController::class, 'assignLeaveGroup'])->name('users.assign-leave-group');
    Route::post('/users/bulk-assign-supervisor', [EmployeeRegController::class, 'bulkAssignSupervisor'])->name('users.bulk-assign-supervisor');

    // Supervisor Management
    Route::get('/supervisor-management', function() {
        $users = \App\Models\User::with(['leaveGroup', 'supervisor', 'subordinates'])->orderBy('name')->get();
        return view('users.supervisor-management', compact('users'));
    })->name('supervisor-management');



// Leave Groups Routes
    Route::resource('leave-groups', LeaveGroupController::class);
    Route::get('/leave-groups/delete/{id}', [LeaveGroupController::class, 'deleteleaveGroup'])->name('leave-groups.delete');

 // Leave Types Routes
    Route::resource('leave-types', LeaveTypeController::class);
    Route::get('/leave-types/delete/{id}', [LeaveTypeController::class, 'deleteleavetype'])->name('leave-types.delete');

// Leave Requests
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::delete('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    Route::get('/leave-requests/{leaveRequest}/download-documentation', [LeaveRequestController::class, 'downloadDocumentation'])->name('leave-requests.download-documentation');
 // Edit functionality for adding/updating documentation
        Route::get('/leave-requests/edit/{leaveRequest}', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit');
        Route::put('/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
// Document management
        Route::get('/{leaveRequest}/download-documentation', [LeaveRequestController::class, 'downloadDocumentation'])
            ->name('download-documentation');

// AJAX route for calculating working days
    Route::post('/leave-requests/calculate-days', [LeaveRequestController::class, 'calculateDays'])->name('leave-requests.calculate-days');

 // Leave Approvals
    Route::get('/leave-approvals', [LeaveRequestController::class, 'approvalList'])->name('leave-requests.approval-list')->middleware('permission:leave-requests.approval-list');
    Route::patch('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::patch('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');


// User Leave Management
    Route::patch('/users/{user}/leave-group', [UserLeaveController::class, 'assignLeaveGroup'])->name('users.assign-leave-group');
    Route::get('/users/{user}/leave-balances', [UserLeaveController::class, 'manageLeaveBalances'])->name('users.leave-balances');
    Route::patch('/leave-balances/{leaveBalance}', [UserLeaveController::class, 'updateLeaveBalance'])->name('leave-balances.update');

// Additional User Leave Management routes
Route::post('/users/{user}/sync-leave-balances', [UserLeaveController::class, 'syncUserLeaveBalances'])->name('users.sync-leave-balances');
Route::post('/leave-groups/{leaveGroup}/sync-balances', [UserLeaveController::class, 'syncLeaveGroupBalances'])->name('leave-groups.sync-balances');

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
Route::get('/api/notifications/recent-unread', [NotificationController::class, 'getRecentUnread'])->name('api.notifications.recent-unread');

  // Bulk Leave Requests (Admin/HR)
Route::get('/leave-requests/bulk/create', [LeaveRequestController::class, 'bulkCreate'])->name('leave-requests.bulk-create')->middleware('permission:leave.bulk-create');
Route::post('/leave-requests/bulk/store', [LeaveRequestController::class, 'bulkStore'])->name('leave-requests.bulk-store');
Route::post('/leave-requests/bulk/import', [LeaveRequestController::class, 'bulkImport'])->name('leave-requests.bulk-import');
Route::get('/leave-requests/bulk/template', [LeaveRequestController::class, 'downloadTemplate'])->name('leave-requests.download-template');




// Add to routes/web.php
Route::middleware(['auth'])->group(function () {

// Daily Leave Records Routes (HR Access)
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/leave-records', [DailyLeaveRecordsController::class, 'index'])->name('leave-records.index');
        Route::get('/leave-records/export', [DailyLeaveRecordsController::class, 'export'])->name('leave-records.export');
        Route::get('leave-records/ajax', [DailyLeaveRecordsController::class, 'getRecordsForDate'])->name('leave-records.ajax');
        Route::get('/leave-records/{leaveRequest}', [DailyLeaveRecordsController::class, 'show'])->name('leave-records.show');
        Route::get('/monthly-leave-report', [DailyLeaveRecordsController::class, 'monthlyReport'])->name('monthly-leave-report');
    });

});



require __DIR__.'/auth.php';

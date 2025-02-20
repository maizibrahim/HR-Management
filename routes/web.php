<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Employee\CountryController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Employee controller
use App\Http\Controllers\Employee\DesignationController;
use App\Http\Controllers\Employee\EmployeeRegController;


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
   Route::get('/edit/user/{id}', 'EditUser')->name('users.edit');
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

//Employee Leave All route
Route::controller(LeaveController::class)->group(function () {
    //Leave Type All route
    Route::get('/all/leave/type', 'AllLeaveType')->name('leave.type.all');
    Route::get('/new/leave/type', 'AddLeaveType')->name('leave.type.add');
    Route::post('/store/leave/type', 'StoreLeaveType')->name('leave.type.store');
    Route::get('/edit/leave/type/{id}', 'EditLeaveType')->name('leave.type.edit');
    Route::post('/update/leave/type/{id}', 'UpdateLeaveType')->name('leave.type.update');
    Route::get('/delete/leave/type/{id}','DeleteLeaveType')->name('leave.type.delete');




});







require __DIR__.'/auth.php';


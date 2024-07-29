<?php

use App\Http\Controllers\Attendance\AttendanceDetailsController;

//user roles


Route::controller(AttendanceDetailsController::class)->group(function () {
    Route::get('Attendance/AttendanceDetails', 'index');
    Route::post('Attendance/AttendanceDetails/data', 'TableView');
    Route::post('Attendance/AttendanceDetails/GetDistrict', 'GetDistrict');    
    Route::post('Attendance/AttendanceDetails/GetInstitute', 'GetInstitute');    
    Route::post('Attendance/AttendanceDetails/GetCourse', 'GetCourse');
    Route::post('Attendance/AttendanceDetails/GetStudents', 'GetStudents');
    Route::get('Attendance/AttendanceDetails/Create', 'Create');
    Route::get('users-and-permissions/user-roles/edit/{RoleID}', 'Edit');
    Route::POST('users-and-permissions/user-roles/json/{RoleID}', 'RoleData');
    Route::post('users-and-permissions/user-roles/create', 'Save');
    Route::POST('users-and-permissions/user-roles/edit/{RoleID}', 'Update');
});

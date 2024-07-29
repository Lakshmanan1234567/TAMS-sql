<?php
use App\Http\Controllers\users\userRoleController;
use App\Http\Controllers\users\userController;
use App\Http\Controllers\users\beneficiaryController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\users\profilecontroller;

//user roles
Route::controller(userRoleController::class)->group(function () {
    Route::get('users-and-permissions/user-roles/', 'index');
    Route::get('users-and-permissions/user-roles/view', 'index');
    Route::post('users-and-permissions/user-roles/data', 'TableView');
    Route::get('users-and-permissions/user-roles/create', 'Create');
    Route::get('users-and-permissions/user-roles/edit/{RoleID}', 'Edit');
    Route::POST('users-and-permissions/user-roles/json/{RoleID}', 'RoleData');
    Route::post('users-and-permissions/user-roles/create', 'Save');
    Route::POST('users-and-permissions/user-roles/edit/{RoleID}', 'Update');
});

Route::controller(userController::class)->group(function () {
    Route::get('users-and-permissions/users/', 'index');
    Route::get('users-and-permissions/users/view', 'index');
    Route::get('users-and-permissions/users/Import', 'Import');
    Route::post('users-and-permissions/users/data', 'TableView');
    Route::get('users-and-permissions/users/create', 'Create');
    Route::get('users-and-permissions/users/edit/{RoleID}', 'Edit');
    Route::post('users-and-permissions/users/create', 'Save');
    Route::POST('users-and-permissions/users/edit/{RoleID}', 'Update');
    Route::get('users-and-permissions/users/delete/{DelID}', 'delete');
    Route::get('/users-and-permissions/users/trash-view', 'TrashView');
    Route::post('/users-and-permissions/users/trash-data', 'TrashTableView');
    Route::post('/users-and-permissions/users/restore/{CID}', 'Restore');
    Route::post('/users-and-permissions/users/get/password', 'getPassword');
    Route::post('/users-and-permissions/import/ThMEMsave', 'ThMEMsave');
});

Route::controller(beneficiaryController::class)->group(function () {
    Route::get('users-and-permissions/Beneficiary/', 'index');
    Route::get('users-and-permissions/Beneficiary/view', 'index');
    Route::get('users-and-permissions/Beneficiary/Import', 'Import');
    Route::post('users-and-permissions/Beneficiary/data', 'TableView');
    Route::get('users-and-permissions/Beneficiary/create', 'Create');
    Route::get('users-and-permissions/Beneficiary/edit/{RoleID}', 'Edit');
    Route::post('users-and-permissions/Beneficiary/create', 'Save');
    Route::POST('users-and-permissions/Beneficiary/edit/{RoleID}', 'Update');
    Route::get('users-and-permissions/Beneficiary/delete/{DelID}', 'delete');
    Route::get('/users-and-permissions/Beneficiary/trash-view', 'TrashView');
    Route::post('/users-and-permissions/Beneficiary/trash-data', 'TrashTableView');
    Route::post('/users-and-permissions/Beneficiary/restore/{CID}', 'Restore');
    Route::post('/users-and-permissions/Beneficiary/get/password', 'getPassword');
    Route::post('/users-and-permissions/import/BENsave', 'BENsave');
});

Route::controller(PasswordController::class)->group(function () {
    Route::get('users-and-permissions/change-password/', 'PasswordChange');
    Route::post('users-and-permissions/PasswordChange', 'UpdatePassword');

});

Route::controller(profilecontroller::class)->group(function () {
    Route::get('users-and-permissions/profile','Profile');
    Route::get('users-and-permissions/UpdateProfile','UpdateProfile');
    Route::post('/users-and-permissions/profileupdate','ProfileUpdate');
    

});
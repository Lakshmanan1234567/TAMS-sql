<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\generalController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProductSpareMapping;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

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



require __DIR__.'/auth.php';
require __DIR__.'/master.php';
require __DIR__.'/users.php';
require __DIR__.'/course.php';
require __DIR__.'/Institute.php';
require __DIR__.'/Enroll.php';
require __DIR__.'/Attendance.php';

// require _DIR_.'/Institute.php';
// require _DIR_.'/Enroll.php';

Route::controller(loginController::class)->group(function () {
    Route::post('/Clogin', 'login');
    // Route::get('/Register','UserRegister');
    Route::post('/cregister','UserRegister');
});
Route::controller(dashboardController::class)->group(function () {
    Route::get('/', 'dashboard');
    Route::get('/dashboard','dashboard');

});

Route::get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	return "Cleared!";
 });


Route::controller(generalController::class)->group(function () {
    Route::post('/Set/Theme/Update','ThemeUpdate');
    Route::post('/get/getMenus','getMenus');
    Route::post('/get/getMenusData','getMenuData');
    Route::post('/Get/Country','GetCountry');
    Route::post('Get/States','GetState');
    Route::post('Get/Gender','GetGender');
    Route::post('/Get/City','GetCity');
    Route::Post('/Get/Taluka','GetTaluka');
    Route::Post('/Get/Block','GetBlock');
    Route::Post('/Get/Village','GetVillage');
    Route::Post('/Get/pincode','Getpincode');
    Route::post('Get/PostalCode','getPostalCode');
    Route::post('Get/Role','RoleData');
});

// Route::controller(ImportController::class)->group(function () {
//     Route::get('/import', 'view');
//     Route::post('/import/create', 'save');
//     Route::post('/import/concreate', 'Consave');
//     Route::post('/import/ThMEMsave', 'ThMEMsave');
    
// });

Route::controller(ImportController::class)->group(function () {
    Route::get('/import', 'index');
    Route::get('/import/view', 'index');
    Route::get('/import/Import', 'Import');
    Route::post('/import/data', 'TableView');
    Route::get('/import/create', 'Create');
    Route::get('/import/edit/{RoleID}', 'Edit');
    Route::post('/import/create', 'Save');
    Route::POST('/import/edit/{RoleID}', 'Update');
    Route::get('/import/delete/{DelID}', 'delete');
    Route::get('/import/trash-view', 'TrashView');
    Route::post('/import/trash-data', 'TrashTableView');
    Route::post('/import/restore/{CID}', 'Restore');
    Route::post('/import/get/password', 'getPassword');
    Route::post('/import/Consave', 'Consave');
    
});



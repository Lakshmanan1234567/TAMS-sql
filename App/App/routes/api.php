<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThOfficerController;

use App\Http\Controllers\Api\LoginController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::Post('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	return "Cleared!";
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->post('/THOfficer/AllPhaseUpdates', function (Request $request) {
//     $controller = new ThOfficerController(); // Replace YourController with the actual name of your controller
//     return $controller->UpdateHouseTypeview($request);
// });

Route::controller(LoginController::class)->group(function () {
    Route::Post('THOfficer/Login','Login');    
});

Route::controller(ThOfficerController::class)->group(function () {
    Route::Post('THOfficer/Details','getUserDetails');    
});


Route::controller(ThOfficerController::class)->group(function () {
    Route::Post('THOfficer/PhaseDetails','getHousePhase');    
});

//Route::match(['post', 'multipart'], 'ThOfficer/Beneficiary', 'ThOfficerController@BenificaryDetails');

Route::controller(ThOfficerController::class)->group(function () {
    Route::Post('THOfficer/Beneficiary','BenificaryDetails');    
});

Route::controller(ThOfficerController::class)->group(function () {
    Route::Post('THOfficer/PhaseUpdate','HisPhase');    
});
Route::controller(ThOfficerController::class)->group(function () {
    Route::Post('THOfficer/PhaseHistory','HistoryPhase'); 
    Route::Post('THOfficer/HisPhasepupdate','HisPhasepupdate'); 
    
});
<?php

use App\Http\Controllers\Institute\InstituteController;
use Illuminate\Support\Facades\Route;


Route::controller(InstituteController::class)->group(function () {
    // Route::get('/institute', 'index');
    Route::get('Institute/', 'index');
    Route::post('/Institute/Approved/data', 'TableViewApproved');
    Route::post('/Institute/Waiting/data', 'TableViewWaiting');
    Route::post('Institute/statusUpdate', 'changeStatus');
    Route::get('/Institute/getCourses/{id}', 'getCourses');
    Route::get('/Institute/create', 'create');
    Route::get('/Institute/edit', 'edit');
    Route::post('Institute/update/data', 'update');
});

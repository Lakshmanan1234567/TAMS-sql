<?php

use App\Http\Controllers\Enroll\EnrollController;
// use App\Http\Controllers\Institute\InstituteController;
use Illuminate\Support\Facades\Route;


Route::controller(EnrollController::class)->group(function () {
    // Route::get('/institute', 'index');
    Route::get('Enroll/', 'index');
    Route::post('/Enroll/data', 'TableView');
    // Route::post('/Enroll/Waiting/data', 'TableViewWaiting'); getCourses
    // Route::post('Enroll/statusUpdate', 'changeStatus');
    // Route::get('/Enroll/getCourses/{id}', 'getCourses');
    Route::get('/Enroll/create', 'create');
    Route::post('/Enroll/save', 'save');
    Route::post('/Enroll/getDistricts', 'getDistricts');
    Route::post('/Enroll/getInstitutes', 'getInstitutes');
    Route::post('/Enroll/getCourses', 'getCourses');
    Route::post('/Enroll/getStudents', 'getStudents');
    // Route::get('/Enroll/edit', 'edit');
    // Route::post('Enroll/update/data', 'update');
});

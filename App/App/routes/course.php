<?php

use App\Http\Controllers\Course\CourseController;

Route::controller(CourseController::class)->group(function () {
    Route::get('/Course', 'view');
    Route::post('/Course/Approved/data', 'TableViewApproved');
    Route::post('/Course/Waiting/data', 'TableViewWaiting');
    Route::get('/Course/create', 'create');
    Route::get('/Course/edit/{Course_Id}', 'edit');
    Route::post('/Course/create', 'save');
    Route::post('/Course/edit/{Course_Id}', 'update');
    Route::post('/Course/delete/{Course_Id}', 'delete');
    Route::get('/Course/trash-view/', 'TrashView');
    Route::post('/Course/trash-data', 'TrashTableView');
    Route::post('/Course/restore/{Course_Id}', 'Restore');
    Route::get('/Course/Import', 'Import');
    Route::post('/Course/statusUpdate', 'changeStatus');
    Route::post('/Course/Import/CUsave', 'CUsave');
});
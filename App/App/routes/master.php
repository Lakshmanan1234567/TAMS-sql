<?php


use App\Http\Controllers\master\HousingTypeController;
use App\Http\Controllers\master\HousingPhaseController;
use App\Http\Controllers\master\ApprovedHousingTypeController;
use App\Http\Controllers\master\AssignOfficerController;
use App\Http\Controllers\master\WorkStatusController;
use App\Http\Controllers\master\PhaseUpdateController;
use App\Http\Controllers\master\PaymentinitiatedController;
use App\Http\Controllers\master\PaymentRelasedController;
use App\Http\Controllers\master\PaymentRecivedController;
use App\Http\Controllers\master\PhaseCompleteController;



Route::controller(HousingTypeController::class)->group(function () {
    Route::get('/master/housingType', 'view');
    Route::post('/master/housingType/data', 'TableView');
    Route::get('/master/housingType/create', 'create');
    Route::get('/master/housingType/edit/{HID}', 'edit');
    Route::post('/master/housingType/create', 'save');
    Route::post('/master/housingType/edit/{HID}', 'update');
    Route::post('/master/housingType/delete/{HID}', 'delete');
    Route::get('/master/housingType/trash-view/', 'TrashView');
    Route::post('/master/housingType/trash-data', 'TrashTableView');
    Route::post('/master/housingType/restore/{HID}', 'Restore');
});

Route::controller(HousingPhaseController::class)->group(function () {
    Route::get('/master/housingPhase', 'view');
    Route::post('/master/housingPhase/data', 'TableView');
    Route::get('/master/housingPhase/create', 'create');
    Route::get('/master/housingPhase/edit/{HPID}', 'edit');
    Route::post('/master/housingPhase/create', 'save');
    Route::post('/master/housingPhase/edit/{HPID}', 'update');
    Route::post('/master/housingPhase/delete/{HPID}', 'delete');
    Route::get('/master/housingPhase/trash-view/', 'TrashView');
    Route::post('/master/housingPhase/trash-data', 'TrashTableView');
    Route::post('/master/housingPhase/restore/{HPID}', 'Restore');
    Route::post('/master/housingphase/gethp','getHP');
    Route::post('/master/housingphase/getben','getben');
    Route::post('/master/housingphase/getbenAll','getbenAll');
    Route::post('/master/housingphase/getCONP','getCONP');
    
    
});
Route::controller(ApprovedHousingTypeController::class)->group(function () {
    Route::get('/master/ApprovedHousingType', 'view');
    Route::post('/master/ApprovedHousingType/data', 'TableView');
    Route::get('/master/ApprovedHousingType/create', 'create');
    Route::get('/master/ApprovedHousingType/edit/{HPID}', 'edit');
    Route::post('/master/ApprovedHousingType/create', 'save');
    Route::post('/master/ApprovedHousingType/edit/{HPID}', 'update');
    Route::post('/master/ApprovedHousingType/delete/{HPID}', 'delete');
    Route::get('/master/ApprovedHousingType/trash-view/', 'TrashView');
    Route::post('/master/ApprovedHousingType/trash-data', 'TrashTableView');
    Route::post('/master/ApprovedHousingType/restore/{HPID}', 'Restore');
    Route::post('/master/ApprovedHousingType/gethT','getHT');
});
Route::controller(AssignOfficerController::class)->group(function () {
    Route::get('/master/AssignedOfficers', 'view');
    Route::post('/master/AssignedOfficers/data', 'TableView');
    Route::get('/master/AssignedOfficers/create', 'create');
    Route::get('/master/AssignedOfficers/edit/{HPID}', 'edit');
    Route::post('/master/AssignedOfficers/create', 'save');
    Route::post('/master/AssignedOfficers/edit/{HPID}', 'update');
    Route::post('/master/AssignedOfficers/delete/{HPID}', 'delete');
    Route::get('/master/AssignedOfficers/trash-view/', 'TrashView');
    Route::post('/master/AssignedOfficers/trash-data', 'TrashTableView');
    Route::post('/master/AssignedOfficers/restore/{HPID}', 'Restore');
    Route::post('/master/AssignedOfficers/getoff','getoff');
    Route::post('/master/AssignedOfficers/import/ASSGsave', 'ASSGsave');
    Route::get('/master/AssignedOfficers/Import', 'Import');
});

Route::controller(WorkStatusController::class)->group(function () {
    Route::get('/master/WorkStatus', 'view');
    Route::post('/master/WorkStatus/data', 'TableView');
    Route::get('/master/WorkStatus/create', 'create');
    Route::get('/master/WorkStatus/edit/{HPID}', 'edit');
    Route::post('/master/WorkStatus/create', 'save');
    Route::post('/master/WorkStatus/edit/{HPID}', 'update');
    Route::post('/master/WorkStatus/delete/{HPID}', 'delete');
    Route::post('/master/WorkStatus/start/{HPID}', 'start');
    Route::get('/master/WorkStatus/trash-view/', 'TrashView');
    Route::post('/master/WorkStatus/trash-data', 'TrashTableView');
    Route::post('/master/WorkStatus/restore/{HPID}', 'Restore');
    Route::post('/master/WorkStatus/getoff','getoff');
    Route::post('/master/WorkStatus/gethp/{HPID}', 'gethp');
    Route::GET('/master/WorkStatus/gethp', 'gethpdata');
    Route::post('/master/WorkStatus/Gethousephase/{HID}', 'Gethousephasedata');
});

Route::controller(PhaseUpdateController::class)->group(function () {
    Route::get('/master/PhaseUpdate', 'view');
    Route::post('/master/PhaseUpdate/data', 'TableViewold');
    Route::get('/master/PhaseUpdate/create', 'create');
    Route::get('/master/PhaseUpdate/edit/{HPID}', 'edit');
    Route::post('/master/PhaseUpdate/create', 'save');
    Route::post('/master/PhaseUpdate/edit/{HPID}', 'update');
    Route::post('/master/PhaseUpdate/delete/{HPID}', 'delete');
    Route::post('/master/PhaseUpdate/start/{HPID}', 'start');
    Route::get('/master/PhaseUpdate/trash-view/', 'TrashView');
    Route::post('/master/PhaseUpdate/trash-data', 'TrashTableView');
    Route::post('/master/PhaseUpdate/restore/{HPID}', 'Restore');
    Route::post('/master/PhaseUpdate/getoff','getoff');
    Route::post('/master/PhaseUpdate/gethp/{HPID}', 'gethp');
    Route::post('/master/PhaseUpdate/Gethousephase/{HID}', 'Gethousephasedata');
    Route::post('/master/PhaseUpdate/housephasedata','housephasedata');
});

Route::controller(PhaseCompleteController::class)->group(function () {
    Route::get('/master/PhaseComplete', 'view');
    Route::post('/master/PhaseComplete/data', 'TableView');
    Route::get('/master/PhaseComplete/create', 'create');
    Route::get('/master/PhaseComplete/edit/{HPID}', 'edit');
    Route::post('/master/PhaseComplete/create', 'save');
    Route::post('/master/PhaseComplete/edit/{HPID}', 'update');
    Route::post('/master/PhaseComplete/delete/{HPID}', 'delete');
    Route::post('/master/PhaseUpdate/PaymentInitiated/{HPID}', 'PaymentInitiated');
    Route::post('/master/PhaseUpdate/PaymentRelased/{HPID}', 'PaymentRelased');
    Route::post('/master/PhaseUpdate/PaymentRecived/{HPID}', 'PaymentRecived');
    Route::post('/master/PhaseComplete/start/{HPID}', 'start');
    Route::get('/master/PhaseComplete/trash-view/', 'TrashView');
    Route::post('/master/PhaseComplete/trash-data', 'TrashTableView');
    Route::post('/master/PhaseComplete/restore/{HPID}', 'Restore');
    Route::post('/master/PhaseComplete/getoff','getoff');
    Route::post('/master/PhaseComplete/gethp/{HPID}', 'gethp');
    Route::post('/master/PhaseComplete/Gethousephase/{HID}', 'Gethousephasedata');
});

Route::controller(PaymentinitiatedController::class)->group(function () {
    Route::get('/master/paymentinitiated', 'view');
    Route::post('/master/paymentinitiated/data', 'TableView');
    Route::get('/master/paymentinitiated/create', 'create');
    Route::get('/master/paymentinitiated/edit/{HPID}', 'edit');
    Route::post('/master/paymentinitiated/create', 'save');
    Route::post('/master/paymentinitiated/edit/{HPID}', 'update');
    Route::post('/master/paymentinitiated/delete/{HPID}', 'delete');
    Route::post('/master/paymentinitiated/start/{HPID}', 'start');
    Route::post('/master/paymentinitiated/initiaze/{HPID}', 'initiaze');
    Route::get('/master/paymentinitiated/trash-view/', 'TrashView');
    Route::post('/master/paymentinitiated/trash-data', 'TrashTableView');
    Route::post('/master/paymentinitiated/restore/{HPID}', 'Restore');
    Route::post('/master/paymentinitiated/getoff','getoff');
    Route::post('/master/paymentinitiated/gethp/{HPID}', 'gethp');
    Route::post('/master/paymentinitiated/Gethousephase/{HID}', 'Gethousephasedata');
    
});

Route::controller(PaymentRelasedController::class)->group(function () {
    Route::get('/master/paymentRelased', 'view');
    Route::post('/master/paymentRelased/data', 'TableView');
    Route::get('/master/paymentRelased/create', 'create');
    Route::get('/master/paymentRelased/edit/{HPID}', 'edit');
    Route::post('/master/paymentRelased/create', 'save');
    Route::post('/master/paymentRelased/edit/{HPID}', 'update');
    Route::post('/master/paymentRelased/delete/{HPID}', 'delete');
    Route::post('/master/paymentRelased/Relased/{HPID}', 'initiaze');
    Route::get('/master/paymentRelased/trash-view/', 'TrashView');
    Route::post('/master/paymentRelased/trash-data', 'TrashTableView');
    Route::post('/master/paymentRelased/restore/{HPID}', 'Restore');
    Route::post('/master/paymentRelased/getoff','getoff');
    Route::post('/master/paymentRelased/gethp/{HPID}', 'gethp');
    Route::post('/master/paymentRelased/Gethousephase/{HID}', 'Gethousephasedata');
});

Route::controller(PaymentRecivedController::class)->group(function () {
    Route::get('/master/paymentRecived', 'view');
    Route::post('/master/paymentRecived/data', 'TableView');
    Route::get('/master/paymentRecived/create', 'create');
    Route::get('/master/paymentRecived/edit/{HPID}', 'edit');
    Route::post('/master/paymentRecived/create', 'save');
    Route::post('/master/paymentRecived/edit/{HPID}', 'update');
    Route::post('/master/paymentRecived/delete/{HPID}', 'delete');
    Route::post('/master/paymentRecived/Recived/{HPID}', 'initiaze');
    Route::get('/master/paymentRecived/trash-view/', 'TrashView');
    Route::post('/master/paymentRecived/trash-data', 'TrashTableView');
    Route::post('/master/paymentRecived/restore/{HPID}', 'Restore');
    Route::post('/master/paymentRecived/getoff','getoff');
    Route::post('/master/paymentRecived/gethp/{HPID}', 'gethp');
    Route::post('/master/paymentRecived/Gethousephase/{HID}', 'Gethousephasedata');
});

<?php

use App\Http\Controllers\Admin\DisplayJobdeskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:operator_display', 'active'])->prefix('operator-display')->name('operator-display.')->group(function () {
    Route::get('/display-jobdesk', [DisplayJobdeskController::class, 'index'])->name('display-jobdesk.index');
    Route::get('/display-jobdesk/manage', [DisplayJobdeskController::class, 'manage'])->name('display-jobdesk.manage');
    Route::get('/display-jobdesk/tv', [DisplayJobdeskController::class, 'tv'])->name('display-jobdesk.tv');
    Route::post('/display-jobdesk/settings', [DisplayJobdeskController::class, 'updateSettings'])->name('display-jobdesk.settings.update');
    Route::post('/display-jobdesk/reset', [DisplayJobdeskController::class, 'reset'])->name('display-jobdesk.reset');
    Route::get('/display-jobdesk/state', [DisplayJobdeskController::class, 'state'])->name('display-jobdesk.state');
    Route::post('/display-jobdesk/scan', [DisplayJobdeskController::class, 'scan'])
        ->middleware('throttle:120,1')
        ->name('display-jobdesk.scan');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\RestaurantWebAddon\App\Http\Controllers\Kitchen as Kitchen;

Route::group(['as' => 'kitchen.', 'prefix' => 'kitchen', 'middleware' => ['chef', 'expired']], function () {
    Route::resource('kots', Kitchen\AcnooKotController::class)->only('index');
    Route::post('kots/cooking-status/{id}', [Kitchen\AcnooKotController::class, 'cookingStatus'])->name('kots-cooking-status');
    Route::post('kots/kot-status/{id}', [Kitchen\AcnooKotController::class, 'kotStatus'])->name('kots-kot-status');
    Route::get('kots/get-ticket/{id}', [Kitchen\AcnooKotController::class, 'getKotTicket'])->name('kots.get-ticket');

    Route::resource('kot-reports', Kitchen\AcnooKotReportController::class)->only('index');
    Route::get('kot-reports/pdf', [Kitchen\AcnooKotReportController::class, 'exportPdf'])->name('kot-reports.pdf');
    Route::get('kot-reports/excel', [Kitchen\AcnooKotReportController::class, 'exportExcel'])->name('kot-reports.excel');
    Route::get('kot-reports/csv', [Kitchen\AcnooKotReportController::class, 'exportCsv'])->name('kot-reports.csv');
});

<?php

use App\Http\Controllers\Admin\RideController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/rides');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/rides', [RideController::class, 'index'])->name('rides.index');
    Route::get('/rides/{ride}', [RideController::class, 'show'])->name('rides.show');
});

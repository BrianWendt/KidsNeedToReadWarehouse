<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::get('/export/purchase_orders', [\App\Http\Controllers\ExportController::class, 'purchase_orders'])->name('export.purchase_orders');

if (env('APP_ENV') == 'local') {
    Route::get('/import', [\App\Http\Controllers\ImportController::class, 'index']);
    Route::get('/correct_books', [\App\Http\Controllers\CorrectBooksController::class, 'index']);
}

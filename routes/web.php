<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments/import', [PaymentController::class, 'import'])->name('payments.import');
    Route::get('/payments/category', [PaymentController::class, 'category'])->name('payments.category');
    Route::post('/payments/add-category', [PaymentController::class, 'addcategory'])->name('payments.add-category');

});

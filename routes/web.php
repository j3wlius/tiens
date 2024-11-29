<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\DistributorController;

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

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments/import', [PaymentController::class, 'import'])->name('payments.import');
    Route::get('/payments/category', [PaymentController::class, 'category'])->name('payments.category');
    Route::post('/payments/add-category', [PaymentController::class, 'addcategory'])->name('payments.add-category');

    // Expense routes
    Route::resource('expenses', ExpenseController::class);
    Route::resource('distributors', DistributorController::class);

    // Role management routes (only accessible by super-admin)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::post('/users/{user}/roles', [RoleController::class, 'assignRole'])->name('users.roles.assign');
    });
});

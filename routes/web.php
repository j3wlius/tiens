<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\SmsController;

Route::get('/', function () {
    return view('auth.login');
});

// Category routes (no auth required)
Route::get('/payments/category', [PaymentController::class, 'category'])->name('payments.category');
Route::post('/payments/add-category', [PaymentController::class, 'addcategory'])->name('payments.add-category');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Management Routes
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Expense Routes
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    
    // Report Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/expense-summary', [ReportController::class, 'expenseSummary'])->name('reports.expense-summary');
    Route::get('/reports/monthly-expense', [ReportController::class, 'monthlyExpenseReport'])->name('reports.monthly-expense');
    Route::get('/reports/distributor-summary', [ReportController::class, 'distributorSummary'])->name('reports.distributor-summary');
    Route::get('/reports/export-expense', [ReportController::class, 'exportExpenseReport'])->name('reports.export-expense');

    // Payment Routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/import', [PaymentController::class, 'import'])->name('payments.import');

    // Distributor routes
    Route::get('/distributors', [DistributorController::class, 'index'])->name('distributors.index');
    Route::get('/distributors/create', [DistributorController::class, 'create'])->name('distributors.create');
    Route::post('/distributors', [DistributorController::class, 'store'])->name('distributors.store');
    Route::get('/distributors/{distributor}/edit', [DistributorController::class, 'edit'])->name('distributors.edit');
    Route::put('/distributors/{distributor}', [DistributorController::class, 'update'])->name('distributors.update');
    Route::delete('/distributors/{distributor}', [DistributorController::class, 'destroy'])->name('distributors.destroy');

    // SMS routes
    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');
});

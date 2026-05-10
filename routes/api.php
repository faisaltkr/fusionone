<?php

use App\Http\Controllers\CompanyRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Middleware\CheckClientId;
use App\Http\Controllers\EInvoiceTransactionLogController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/{id}', [SalesController::class, 'show'])->name('sales.show');
Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::put('/sales/{id}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchase.store');
Route::put('/purchases/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

Route::get('/companies', [CompanyRegistrationController::class, 'index'])->name('companies.index');
Route::get('/companies/{id}', [CompanyRegistrationController::class, 'show'])->name('companies.show');
Route::post('/register-company', [CompanyRegistrationController::class, 'register'])->name('companies.register');
Route::delete('/companies/{id}', [CompanyRegistrationController::class, 'destroy'])->name('companies.destroy');

Route::get('/invoice-logs', [EInvoiceTransactionLogController::class, 'index'])->name('invoice-logs.index');
Route::get('/invoice-logs/{id}', [EInvoiceTransactionLogController::class, 'show'])->name('invoice-logs.show');
Route::post('/invoice-logs', [EInvoiceTransactionLogController::class, 'store'])->name('invoice-logs.store');
Route::delete('/invoice-logs/{id}', [EInvoiceTransactionLogController::class, 'destroy'])->name('invoice-logs.destroy');
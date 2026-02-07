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


Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchase.store');
Route::put('/sales/{id}', [SalesController::class, 'update'])->name('sales.update');
Route::post('/register-company', [CompanyRegistrationController::class, 'register']);
Route::post('/invoice-logs', [EInvoiceTransactionLogController::class, 'store']);
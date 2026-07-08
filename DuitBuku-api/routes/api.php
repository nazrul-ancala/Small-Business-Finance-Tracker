<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\TransactionController;
use App\Http\Controllers\Manage\CategoryController;
use App\Http\Controllers\Manage\RecurringEntryController;
use App\Http\Controllers\Manage\CustomerController;
use App\Http\Controllers\Manage\InvoiceController;
use App\Http\Controllers\Manage\PaymentRecordController;
use App\Http\Controllers\Manage\DrawingController;
use App\Http\Controllers\Manage\CashflowEntryController;
use App\Http\Controllers\Manage\PLController;

Route::get('/health', fn () => response()->json(['Success' => true, 'Message' => 'API is running.']));

Route::middleware('basic.token')->group(function () {

    // Transactions
    Route::get( '/Transaction/GET_TransactionList',                [TransactionController::class,    'GET_TransactionList']);
    Route::get( '/Transaction/GET_SpecificTransaction',            [TransactionController::class,    'GET_SpecificTransaction']);
    Route::post('/Transaction/POST_Transaction_SaveUpdateDelete',  [TransactionController::class,    'POST_Transaction_SaveUpdateDelete']);

    // Categories
    Route::get( '/Category/GET_CategoryList',                      [CategoryController::class,       'GET_CategoryList']);
    Route::get( '/Category/GET_SpecificCategory',                  [CategoryController::class,       'GET_SpecificCategory']);
    Route::post('/Category/POST_Category_SaveUpdateDelete',        [CategoryController::class,       'POST_Category_SaveUpdateDelete']);

    // Recurring Entries
    Route::get( '/RecurringEntry/GET_RecurringEntryList',                     [RecurringEntryController::class, 'GET_RecurringEntryList']);
    Route::get( '/RecurringEntry/GET_SpecificRecurringEntry',                 [RecurringEntryController::class, 'GET_SpecificRecurringEntry']);
    Route::post('/RecurringEntry/POST_RecurringEntry_SaveUpdateDelete',       [RecurringEntryController::class, 'POST_RecurringEntry_SaveUpdateDelete']);

    // Customers
    Route::get( '/Customer/GET_CustomerList',                 [CustomerController::class,      'GET_CustomerList']);
    Route::get( '/Customer/GET_SpecificCustomer',             [CustomerController::class,      'GET_SpecificCustomer']);
    Route::post('/Customer/POST_Customer_SaveUpdateDelete',   [CustomerController::class,      'POST_Customer_SaveUpdateDelete']);

    // Invoices
    Route::get( '/Invoice/GET_InvoiceList',                   [InvoiceController::class,       'GET_InvoiceList']);
    Route::get( '/Invoice/GET_SpecificInvoice',               [InvoiceController::class,       'GET_SpecificInvoice']);
    Route::post('/Invoice/POST_Invoice_SaveUpdateDelete',     [InvoiceController::class,       'POST_Invoice_SaveUpdateDelete']);

    // Payment Records
    Route::get( '/PaymentRecord/GET_PaymentRecordList',               [PaymentRecordController::class, 'GET_PaymentRecordList']);
    Route::get( '/PaymentRecord/GET_SpecificPaymentRecord',           [PaymentRecordController::class, 'GET_SpecificPaymentRecord']);
    Route::post('/PaymentRecord/POST_PaymentRecord_SaveUpdateDelete', [PaymentRecordController::class, 'POST_PaymentRecord_SaveUpdateDelete']);

    // Drawings
    Route::get( '/Drawing/GET_DrawingList',               [DrawingController::class,       'GET_DrawingList']);
    Route::get( '/Drawing/GET_SpecificDrawing',           [DrawingController::class,       'GET_SpecificDrawing']);
    Route::get( '/Drawing/GET_SalarySummary',             [DrawingController::class,       'GET_SalarySummary']);
    Route::post('/Drawing/POST_Drawing_SaveUpdateDelete', [DrawingController::class,       'POST_Drawing_SaveUpdateDelete']);

    // Cashflow Entries
    Route::get( '/CashflowEntry/GET_CashflowEntryList',               [CashflowEntryController::class, 'GET_CashflowEntryList']);
    Route::get( '/CashflowEntry/GET_SpecificCashflowEntry',           [CashflowEntryController::class, 'GET_SpecificCashflowEntry']);
    Route::post('/CashflowEntry/POST_CashflowEntry_SaveUpdateDelete', [CashflowEntryController::class, 'POST_CashflowEntry_SaveUpdateDelete']);

    // P&L Reports
    Route::get('/PL/GET_PLMonth',     [PLController::class, 'GET_PLMonth']);
    Route::get('/PL/GET_PLQuarterly', [PLController::class, 'GET_PLQuarterly']);
    Route::get('/PL/GET_PLYearly',    [PLController::class, 'GET_PLYearly']);

});

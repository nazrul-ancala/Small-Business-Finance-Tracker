<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landing\HomepageController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\dashboard\manageDashboardController;
use App\Http\Controllers\transactions\manageAllTransactionsController;
use App\Http\Controllers\transactions\manageRecurringEntriesController;
use App\Http\Controllers\transactions\manageCategoriesController;
use App\Http\Controllers\invoices\manageAllInvoicesController;
use App\Http\Controllers\invoices\manageCustomersController;
use App\Http\Controllers\invoices\managePaymentRecordsController;
use App\Http\Controllers\drawings\manageAllDrawingsController;
use App\Http\Controllers\drawings\manageSalarySummaryController;
use App\Http\Controllers\cashflow\manageCashflowCalendarController;
use App\Http\Controllers\cashflow\manageUpcomingBillsController;
use App\Http\Controllers\cashflow\manageExpectedIncomeController;
use App\Http\Controllers\cashflow\manageDaysCashLeftController;
use App\Http\Controllers\pl\managePLMonthController;
use App\Http\Controllers\pl\managePLQuarterlyController;
use App\Http\Controllers\pl\managePLYearlyController;
use App\Http\Controllers\business\manageBusinessHealthController;
use App\Http\Controllers\account\manageProfileController;
use App\Http\Controllers\account\manageSettingsController;

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [manageDashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::get( '/transactions',                    [manageAllTransactionsController::class,  'index'])->name('transactions.all');
    Route::post('/transactions/save',               [manageAllTransactionsController::class,  'save']);
    Route::post('/transactions/update',             [manageAllTransactionsController::class,  'update']);
    Route::post('/transactions/delete',             [manageAllTransactionsController::class,  'delete']);

    Route::get( '/transactions/recurring',          [manageRecurringEntriesController::class, 'index'])->name('transactions.recurring');
    Route::post('/transactions/recurring/save',     [manageRecurringEntriesController::class, 'save']);
    Route::post('/transactions/recurring/apply',    [manageRecurringEntriesController::class, 'apply']);
    Route::post('/transactions/recurring/delete',   [manageRecurringEntriesController::class, 'delete']);

    Route::get( '/transactions/categories',         [manageCategoriesController::class,       'index'])->name('transactions.categories');
    Route::post('/transactions/categories/save',    [manageCategoriesController::class,       'save']);
    Route::post('/transactions/categories/delete',  [manageCategoriesController::class,       'delete']);

    // Invoices
    Route::get( '/invoices',                    [manageAllInvoicesController::class,    'index'])->name('invoices.all');
    Route::post('/invoices/save',               [manageAllInvoicesController::class,    'save']);
    Route::post('/invoices/update',             [manageAllInvoicesController::class,    'update']);
    Route::post('/invoices/delete',             [manageAllInvoicesController::class,    'delete']);
    Route::post('/invoices/update-status',      [manageAllInvoicesController::class,    'updateStatus']);

    Route::get( '/invoices/customers',          [manageCustomersController::class,      'index'])->name('invoices.customers');
    Route::post('/invoices/customers/save',     [manageCustomersController::class,      'save']);
    Route::post('/invoices/customers/update',   [manageCustomersController::class,      'update']);
    Route::post('/invoices/customers/delete',   [manageCustomersController::class,      'delete']);

    Route::get( '/invoices/payments',           [managePaymentRecordsController::class, 'index'])->name('invoices.payments');
    Route::post('/invoices/payments/save',      [managePaymentRecordsController::class, 'save']);
    Route::post('/invoices/payments/delete',    [managePaymentRecordsController::class, 'delete']);

    // Owner Drawings
    Route::get( '/drawings',        [manageAllDrawingsController::class,  'index'])->name('drawings.all');
    Route::post('/drawings/save',   [manageAllDrawingsController::class,  'save']);
    Route::post('/drawings/delete', [manageAllDrawingsController::class,  'delete']);
    Route::get( '/drawings/salary', [manageSalarySummaryController::class,'index'])->name('drawings.salary');

    // Cashflow Forecast
    Route::get( '/cashflow/calendar',             [manageCashflowCalendarController::class, 'index'])->name('cashflow.calendar');
    Route::get( '/cashflow/bills',                [manageUpcomingBillsController::class,    'index'])->name('cashflow.bills');
    Route::post('/cashflow/bills/save',           [manageUpcomingBillsController::class,    'save']);
    Route::post('/cashflow/bills/update-status',  [manageUpcomingBillsController::class,    'updateStatus']);
    Route::post('/cashflow/bills/delete',         [manageUpcomingBillsController::class,    'delete']);
    Route::get( '/cashflow/income',               [manageExpectedIncomeController::class,   'index'])->name('cashflow.income');
    Route::post('/cashflow/income/save',          [manageExpectedIncomeController::class,   'save']);
    Route::post('/cashflow/income/update-status', [manageExpectedIncomeController::class,   'updateStatus']);
    Route::post('/cashflow/income/delete',        [manageExpectedIncomeController::class,   'delete']);
    Route::get( '/cashflow/days',                 [manageDaysCashLeftController::class,     'index'])->name('cashflow.days');

    // P&L Snapshot
    Route::get('/pl/month',     [managePLMonthController::class,     'index'])->name('pl.month');
    Route::get('/pl/quarterly', [managePLQuarterlyController::class, 'index'])->name('pl.quarterly');
    Route::get('/pl/yearly',    [managePLYearlyController::class,    'index'])->name('pl.yearly');

    // Business Health
    Route::get('/business/health', [manageBusinessHealthController::class, 'index'])->name('business.health');

    // Account
    Route::get( '/profile',           [manageProfileController::class,  'index'])->name('profile');
    Route::get( '/settings',          [manageSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/update',   [manageSettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/password', [manageSettingsController::class, 'updatePassword'])->name('settings.password');

});

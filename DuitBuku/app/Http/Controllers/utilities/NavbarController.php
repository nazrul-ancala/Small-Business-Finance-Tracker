<?php

namespace App\Http\Controllers\utilities;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class NavbarController extends Controller
{
    public function isActive(string $routeName): string
    {
        return Route::currentRouteName() === $routeName ? 'active' : '';
    }

    public function dashboardIsActive(): string
    {
        return $this->isActive('dashboard');
    }

    public function txnIsExpanded(): string
    {
        $routes = ['transactions.all', 'transactions.recurring', 'transactions.categories'];
        return in_array(Route::currentRouteName(), $routes) ? 'show' : '';
    }
    public function invIsExpanded(): string
    {
        $routes = ['invoices.all', 'invoices.customers', 'invoices.payments'];
        return in_array(Route::currentRouteName(), $routes) ? 'show' : '';
    }
    public function drawIsExpanded(): string
    {
        $routes = ['drawings.all', 'drawings.salary'];
        return in_array(Route::currentRouteName(), $routes) ? 'show' : '';
    }
    public function cfIsExpanded(): string
    {
        $routes = ['cashflow.calendar', 'cashflow.bills', 'cashflow.income', 'cashflow.days'];
        return in_array(Route::currentRouteName(), $routes) ? 'show' : '';
    }
    public function plIsExpanded(): string
    {
        $routes = ['pl.month', 'pl.quarterly', 'pl.yearly'];
        return in_array(Route::currentRouteName(), $routes) ? 'show' : '';
    }

    private ?float $currentBalance = null;

    public function getCurrentBalance(): float
    {
        if ($this->currentBalance !== null) {
            return $this->currentBalance;
        }

        $txRes  = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

        $income  = array_sum(array_map(fn($t) => $t->type === 'income'  ? (float) $t->amount : 0, $txList));
        $expense = array_sum(array_map(fn($t) => $t->type === 'expense' ? (float) $t->amount : 0, $txList));

        return $this->currentBalance = $income - $expense;
    }
}

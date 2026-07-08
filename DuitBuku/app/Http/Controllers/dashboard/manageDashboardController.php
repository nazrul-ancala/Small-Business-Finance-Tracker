<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Concerns\ComputesBusinessHealth;
use App\Http\Controllers\Controller;

class manageDashboardController extends Controller
{
    use ComputesBusinessHealth;

    public function index()
    {
        $txRes  = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

        [
            'score' => $score,
            'profitMargin' => $profitMargin,
            'cashRunway' => $cashRunway,
            'netProfit' => $netProfit,
        ] = $this->computeHealthMetrics($txList);

        $currentMonth = date('Y-m');
        $incomeThisMonth = array_sum(array_map(
            fn($t) => ($t->type === 'income' && isset($t->date) && date('Y-m', strtotime($t->date)) === $currentMonth) ? (float) $t->amount : 0,
            $txList
        ));
        $expenseThisMonth = array_sum(array_map(
            fn($t) => ($t->type === 'expense' && isset($t->date) && date('Y-m', strtotime($t->date)) === $currentMonth) ? (float) $t->amount : 0,
            $txList
        ));

        $recentTransactions = array_slice($txList, 0, 5);

        $invRes = $this->callApi('GET', 'Invoice/GET_InvoiceList');
        $invoices = array_map(fn($i) => (object) $i, $invRes['Data'] ?? []);
        $pendingInvoicesCount = count(array_filter($invoices, fn($i) => $i->status !== 'paid'));

        $billsRes = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', ['type' => 'bill', 'status' => 'pending']);
        $bills = array_map(fn($b) => (object) $b, $billsRes['Data'] ?? []);
        $upcomingBills = array_slice($bills, 0, 5);

        $catRes = $this->callApi('GET', 'Category/GET_CategoryList');
        $allCats = array_map(fn($c) => (object) $c, $catRes['Data'] ?? []);
        $incomeCats  = array_values(array_filter($allCats, fn($c) => $c->type === 'income'));
        $expenseCats = array_values(array_filter($allCats, fn($c) => $c->type === 'expense'));

        return view('dashboard.dashboard', compact(
            'incomeThisMonth', 'expenseThisMonth', 'netProfit', 'pendingInvoicesCount',
            'recentTransactions', 'score', 'profitMargin', 'cashRunway',
            'upcomingBills', 'incomeCats', 'expenseCats'
        ));
    }
}

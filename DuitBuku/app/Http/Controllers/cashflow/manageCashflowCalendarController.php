<?php

namespace App\Http\Controllers\cashflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageCashflowCalendarController extends Controller
{
    public function index(Request $request)
    {
        $today     = date('Y-m-d');
        $in30days  = date('Y-m-d', strtotime('+30 days'));

        // All pending bills + income (no month filter — we filter in PHP for next 30 days)
        $billsRes  = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', ['type' => 'bill',   'status' => 'pending']);
        $bills     = array_map(fn($b) => (object) $b, $billsRes['Data'] ?? []);

        $incomeRes = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', ['type' => 'income', 'status' => 'pending']);
        $incomes   = array_map(fn($i) => (object) $i, $incomeRes['Data'] ?? []);

        // Summary cards — bills/income due in next 30 days
        $upcomingBills   = array_filter($bills,   fn($b) => $b->expected_date >= $today && $b->expected_date <= $in30days);
        $upcomingIncomes = array_filter($incomes, fn($i) => $i->expected_date >= $today && $i->expected_date <= $in30days);

        $totalBills  = array_sum(array_map(fn($b) => $b->amount, $upcomingBills));
        $totalIncome = array_sum(array_map(fn($i) => $i->amount, $upcomingIncomes));
        $netCashflow = $totalIncome - $totalBills;

        // Combined upcoming list (next 30 days, sorted by date)
        $combined = [];
        foreach ($bills as $b) {
            if ($b->expected_date >= $today && $b->expected_date <= $in30days) {
                $combined[] = (object)[
                    'type'          => 'bill',
                    'expected_date' => $b->expected_date,
                    'category_name' => $b->category_name ?? null,
                    'notes'         => $b->notes ?? null,
                    'amount'        => $b->amount,
                    'status'        => $b->status,
                ];
            }
        }
        foreach ($incomes as $i) {
            if ($i->expected_date >= $today && $i->expected_date <= $in30days) {
                $combined[] = (object)[
                    'type'          => 'income',
                    'expected_date' => $i->expected_date,
                    'category_name' => $i->category_name ?? null,
                    'notes'         => $i->notes ?? null,
                    'amount'        => $i->amount,
                    'status'        => $i->status,
                ];
            }
        }
        usort($combined, fn($a, $b) => strcmp($a->expected_date, $b->expected_date));

        // Days runway from transactions
        $txRes    = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList   = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

        $incomeTotal  = array_sum(array_map(fn($t) => $t->type === 'income'  ? (float)$t->amount : 0, $txList));
        $expenseTotal = array_sum(array_map(fn($t) => $t->type === 'expense' ? (float)$t->amount : 0, $txList));
        $currentBalance = $incomeTotal - $expenseTotal;

        $cutoff = date('Y-m-d', strtotime('-30 days'));
        $last30Expense = array_sum(array_map(
            fn($t) => ($t->type === 'expense' && isset($t->date) && $t->date >= $cutoff) ? (float)$t->amount : 0,
            $txList
        ));
        $avgDailyExpense = $last30Expense / 30;
        $daysRunway = $avgDailyExpense > 0 ? (int) floor($currentBalance / $avgDailyExpense) : 0;

        return view('cashflow.calendar_view', compact(
            'totalBills', 'totalIncome', 'netCashflow', 'currentBalance', 'daysRunway', 'combined', 'in30days'
        ));
    }
}

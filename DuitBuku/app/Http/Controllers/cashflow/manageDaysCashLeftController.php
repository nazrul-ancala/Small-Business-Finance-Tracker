<?php

namespace App\Http\Controllers\cashflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageDaysCashLeftController extends Controller
{
    public function index(Request $request)
    {
        // All transactions for current balance
        $txRes  = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

        $incomeTotal  = array_sum(array_map(fn($t) => $t->type === 'income'  ? (float)$t->amount : 0, $txList));
        $expenseTotal = array_sum(array_map(fn($t) => $t->type === 'expense' ? (float)$t->amount : 0, $txList));
        $currentBalance = $incomeTotal - $expenseTotal;

        // Avg daily expense from last 30 days
        $cutoff = date('Y-m-d', strtotime('-30 days'));
        $last30Expense = array_sum(array_map(
            fn($t) => ($t->type === 'expense' && isset($t->date) && $t->date >= $cutoff) ? (float)$t->amount : 0,
            $txList
        ));
        $avgDailyExpense = $last30Expense / 30;
        $daysRunway = $avgDailyExpense > 0 ? max(0, (int) floor($currentBalance / $avgDailyExpense)) : 0;

        // Weekly averages from last 28 days
        $last28Cutoff = date('Y-m-d', strtotime('-28 days'));
        $last28Income  = array_sum(array_map(
            fn($t) => ($t->type === 'income'  && isset($t->date) && $t->date >= $last28Cutoff) ? (float)$t->amount : 0,
            $txList
        ));
        $last28Expense = array_sum(array_map(
            fn($t) => ($t->type === 'expense' && isset($t->date) && $t->date >= $last28Cutoff) ? (float)$t->amount : 0,
            $txList
        ));
        $avgWeeklyIncome  = $last28Income  / 4;
        $avgWeeklyExpense = $last28Expense / 4;

        // Pending cashflow entries for next 4 weeks
        $cfRes      = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', ['status' => 'pending']);
        $cfEntries  = array_map(fn($e) => (object) $e, $cfRes['Data'] ?? []);

        // Build 4-week projection
        $projection     = [];
        $runningBalance = $currentBalance;
        for ($w = 1; $w <= 4; $w++) {
            $weekStart = date('Y-m-d', strtotime('+' . (($w - 1) * 7)     . ' days'));
            $weekEnd   = date('Y-m-d', strtotime('+' . (($w * 7) - 1)     . ' days'));
            $weekLabel = date('d M', strtotime($weekStart)) . ' – ' . date('d M', strtotime($weekEnd));

            $cfIncome  = array_sum(array_map(
                fn($e) => ($e->type === 'income'  && $e->expected_date >= $weekStart && $e->expected_date <= $weekEnd) ? (float)$e->amount : 0,
                $cfEntries
            ));
            $cfExpense = array_sum(array_map(
                fn($e) => ($e->type === 'bill'    && $e->expected_date >= $weekStart && $e->expected_date <= $weekEnd) ? (float)$e->amount : 0,
                $cfEntries
            ));

            $projIncome  = $avgWeeklyIncome  + $cfIncome;
            $projExpense = $avgWeeklyExpense + $cfExpense;
            $net         = $projIncome - $projExpense;
            $runningBalance += $net;

            $projection[] = [
                'week'            => $weekLabel,
                'proj_income'     => $projIncome,
                'proj_expense'    => $projExpense,
                'net'             => $net,
                'running_balance' => $runningBalance,
            ];
        }

        return view('cashflow.days_cash_left', compact(
            'currentBalance', 'avgDailyExpense', 'daysRunway', 'projection'
        ));
    }
}

<?php

namespace App\Http\Controllers\Concerns;

trait ComputesBusinessHealth
{
    protected function computeHealthMetrics(array $txList): array
    {
        $totalIncome  = array_sum(array_map(fn($t) => $t->type === 'income'  ? (float) $t->amount : 0, $txList));
        $totalExpense = array_sum(array_map(fn($t) => $t->type === 'expense' ? (float) $t->amount : 0, $txList));
        $netProfit    = $totalIncome - $totalExpense;

        $profitMargin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0;
        $expenseRatio = $totalIncome > 0 ? ($totalExpense / $totalIncome) * 100 : 0;

        $currentMonth     = date('Y-m');
        $revenueThisMonth = array_sum(array_map(
            fn($t) => ($t->type === 'income' && isset($t->date) && date('Y-m', strtotime($t->date)) === $currentMonth) ? (float) $t->amount : 0,
            $txList
        ));

        $currentBalance = $totalIncome - $totalExpense;

        $cutoff = date('Y-m-d', strtotime('-30 days'));
        $last30Expense = array_sum(array_map(
            fn($t) => ($t->type === 'expense' && isset($t->date) && $t->date >= $cutoff) ? (float) $t->amount : 0,
            $txList
        ));
        $avgDailyExpense = $last30Expense / 30;
        $cashRunway      = $avgDailyExpense > 0 ? (int) floor($currentBalance / $avgDailyExpense) : 0;

        $marginScore  = $profitMargin >= 30 ? 100 : max(0, $profitMargin / 30 * 100);
        $expenseScore = $expenseRatio <= 50 ? 100 : ($expenseRatio >= 100 ? 0 : 100 - (($expenseRatio - 50) / 50 * 100));
        $runwayScore  = $cashRunway >= 90 ? 100 : max(0, $cashRunway / 90 * 100);
        $score        = (int) round(($marginScore + $expenseScore + $runwayScore) / 3);

        return compact(
            'score', 'profitMargin', 'expenseRatio', 'revenueThisMonth', 'cashRunway',
            'totalIncome', 'totalExpense', 'netProfit'
        );
    }
}

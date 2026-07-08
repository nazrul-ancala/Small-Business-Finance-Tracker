<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Controller;

class manageBusinessHealthController extends Controller
{
    public function index()
    {
        $txRes  = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

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

        $insights = [];

        if (count($txList) === 0) {
            $insights[] = [
                'icon'  => 'ri-information-line',
                'color' => 'info',
                'title' => 'No transactions yet',
                'text'  => 'Add transactions to generate financial insights and update your health score.',
            ];
        } else {
            if ($profitMargin < 0) {
                $insights[] = [
                    'icon'  => 'ri-error-warning-line',
                    'color' => 'danger',
                    'title' => 'Operating at a loss',
                    'text'  => 'Expenses currently exceed income. Review spending or increase revenue.',
                ];
            }

            if ($expenseRatio > 80) {
                $insights[] = [
                    'icon'  => 'ri-alert-line',
                    'color' => 'warning',
                    'title' => 'High expense ratio',
                    'text'  => 'Expenses are taking up ' . number_format($expenseRatio, 1) . '% of revenue. Consider trimming costs.',
                ];
            }

            if ($cashRunway < 30) {
                $insights[] = [
                    'icon'  => 'ri-error-warning-line',
                    'color' => 'danger',
                    'title' => 'Low cash runway',
                    'text'  => 'At current spending, cash on hand covers only ' . $cashRunway . ' days. Build up a buffer.',
                ];
            }

            if ($profitMargin >= 20) {
                $insights[] = [
                    'icon'  => 'ri-checkbox-circle-line',
                    'color' => 'success',
                    'title' => 'Healthy profit margin',
                    'text'  => 'Profit margin is ' . number_format($profitMargin, 1) . '% — well above the typical small-business benchmark.',
                ];
            }

            if ($cashRunway >= 90) {
                $insights[] = [
                    'icon'  => 'ri-checkbox-circle-line',
                    'color' => 'success',
                    'title' => 'Strong cash runway',
                    'text'  => 'Current balance covers ' . $cashRunway . '+ days of expenses at the recent burn rate.',
                ];
            }

            if (count($insights) === 0) {
                $insights[] = [
                    'icon'  => 'ri-checkbox-circle-line',
                    'color' => 'success',
                    'title' => 'Business profile ready',
                    'text'  => 'Your account is set up and ready to start tracking financial health.',
                ];
            }
        }

        $insights = array_slice($insights, 0, 3);

        return view('business.health', compact(
            'score', 'profitMargin', 'expenseRatio', 'revenueThisMonth', 'cashRunway',
            'totalIncome', 'totalExpense', 'netProfit', 'insights'
        ));
    }
}

<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Concerns\ComputesBusinessHealth;
use App\Http\Controllers\Controller;

class manageBusinessHealthController extends Controller
{
    use ComputesBusinessHealth;

    public function index()
    {
        $txRes  = $this->callApi('GET', 'Transaction/GET_TransactionList');
        $txList = array_map(fn($t) => (object) $t, $txRes['Data'] ?? []);

        [
            'score' => $score,
            'profitMargin' => $profitMargin,
            'expenseRatio' => $expenseRatio,
            'revenueThisMonth' => $revenueThisMonth,
            'cashRunway' => $cashRunway,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
        ] = $this->computeHealthMetrics($txList);

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

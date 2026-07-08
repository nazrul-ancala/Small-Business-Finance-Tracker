<?php

namespace App\Http\Controllers\pl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class managePLYearlyController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));

        $response = $this->callApi('GET', 'PL/GET_PLYearly', ['year' => $year]);
        $rows     = array_map(fn($r) => (object) $r, $response['Data'] ?? []);
        $byKey    = [];
        foreach ($rows as $r) {
            $byKey[$r->month_key] = $r;
        }

        $monthlyBreakdown = [];
        $runningBalance   = 0.0;
        for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
            $monthKey = sprintf('%04d-%02d', $year, $monthNum);
            $label    = date('F Y', mktime(0, 0, 0, $monthNum, 1, $year));

            $income  = isset($byKey[$monthKey]) ? (float) $byKey[$monthKey]->income  : 0.0;
            $expense = isset($byKey[$monthKey]) ? (float) $byKey[$monthKey]->expense : 0.0;
            $net     = $income - $expense;
            $runningBalance += $net;

            $monthlyBreakdown[] = [
                'month_key'       => $monthKey,
                'month_label'     => $label,
                'month_num'       => $monthNum,
                'income'          => $income,
                'expense'         => $expense,
                'net'             => $net,
                'running_balance' => $runningBalance,
            ];
        }

        $totalIncome  = array_sum(array_column($monthlyBreakdown, 'income'));
        $totalExpense = array_sum(array_column($monthlyBreakdown, 'expense'));
        $netProfit    = $totalIncome - $totalExpense;

        return view('pl.yearly_summary', compact(
            'monthlyBreakdown', 'year', 'totalIncome', 'totalExpense', 'netProfit'
        ));
    }
}

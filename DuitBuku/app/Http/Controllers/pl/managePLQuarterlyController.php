<?php

namespace App\Http\Controllers\pl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class managePLQuarterlyController extends Controller
{
    public function index(Request $request)
    {
        $year    = (int) $request->input('year', date('Y'));
        $quarter = (int) $request->input('quarter', ceil(date('n') / 3));

        $response = $this->callApi('GET', 'PL/GET_PLQuarterly', ['year' => $year, 'quarter' => $quarter]);
        $rows     = array_map(fn($r) => (object) $r, $response['Data'] ?? []);
        $byKey    = [];
        foreach ($rows as $r) {
            $byKey[$r->month_key] = $r;
        }

        $monthlyBreakdown = [];
        for ($i = 1; $i <= 3; $i++) {
            $monthNum = ($quarter - 1) * 3 + $i;
            $monthKey = sprintf('%04d-%02d', $year, $monthNum);
            $label    = date('F Y', mktime(0, 0, 0, $monthNum, 1, $year));

            $income  = isset($byKey[$monthKey]) ? (float) $byKey[$monthKey]->income  : 0.0;
            $expense = isset($byKey[$monthKey]) ? (float) $byKey[$monthKey]->expense : 0.0;
            $net     = $income - $expense;

            $monthlyBreakdown[] = [
                'month_key'    => $monthKey,
                'month_label'  => $label,
                'income'       => $income,
                'expense'      => $expense,
                'net'          => $net,
                'gross_margin' => $income > 0 ? ($net / $income) * 100 : null,
            ];
        }

        $totalIncome  = array_sum(array_column($monthlyBreakdown, 'income'));
        $totalExpense = array_sum(array_column($monthlyBreakdown, 'expense'));
        $netProfit    = $totalIncome - $totalExpense;

        return view('pl.quarterly_view', compact(
            'monthlyBreakdown', 'year', 'quarter', 'totalIncome', 'totalExpense', 'netProfit'
        ));
    }
}

<?php

namespace App\Http\Controllers\pl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class managePLMonthController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m'));

        $response  = $this->callApi('GET', 'PL/GET_PLMonth', ['month' => $month]);
        $breakdown = array_map(fn($r) => (object) $r, $response['Data'] ?? []);

        $totalIncome  = array_sum(array_map(fn($r) => (float) $r->income, $breakdown));
        $totalExpense = array_sum(array_map(fn($r) => (float) $r->expense, $breakdown));
        $netProfit    = $totalIncome - $totalExpense;

        return view('pl.this_month', compact('breakdown', 'month', 'totalIncome', 'totalExpense', 'netProfit'));
    }
}

<?php

namespace App\Http\Controllers\drawings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageSalarySummaryController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $response       = $this->callApi('GET', 'Drawing/GET_SalarySummary', ['year' => $year]);
        $monthlySummary = array_map(fn($r) => (object) $r, $response['Data'] ?? []);

        $total = array_sum(array_map(fn($r) => $r->total_amount, $monthlySummary));
        $count = count($monthlySummary);
        $avg   = $count > 0 ? $total / $count : 0;

        // This-month salary total (current month, regardless of year filter)
        $currentMonthKey = date('Y-m');
        $thisMonth       = 0;
        if ((string) $year === date('Y')) {
            foreach ($monthlySummary as $row) {
                if ($row->month_key === $currentMonthKey) {
                    $thisMonth = $row->total_amount;
                    break;
                }
            }
        }

        $summary = [
            'total'      => $total,
            'this_month' => $thisMonth,
            'avg'        => $avg,
        ];

        return view('drawings.salary_summary', compact('monthlySummary', 'summary', 'year'));
    }
}

<?php

namespace App\Http\Controllers\cashflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageUpcomingBillsController extends Controller
{
    public function index(Request $request)
    {
        $month  = $request->input('month', '');
        $status = $request->input('status', '');

        $params = ['type' => 'bill', 'month' => $month];
        if ($status) $params['status'] = $status;

        $billsRes = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', $params);
        $bills    = array_map(fn($b) => (object) $b, $billsRes['Data'] ?? []);

        $today      = date('Y-m-d');
        $weekLater  = date('Y-m-d', strtotime('+7 days'));

        $total    = array_sum(array_map(fn($b) => $b->amount, $bills));
        $dueWeek  = array_sum(array_map(
            fn($b) => ($b->status === 'pending' && $b->expected_date >= $today && $b->expected_date <= $weekLater) ? $b->amount : 0,
            $bills
        ));
        $overdue  = count(array_filter(
            $bills,
            fn($b) => $b->status === 'pending' && $b->expected_date < $today
        ));

        $summary = ['total' => $total, 'due_week' => $dueWeek, 'overdue' => $overdue];

        $catRes     = $this->callApi('GET', 'Category/GET_CategoryList');
        $allCats    = array_map(fn($c) => (object) $c, $catRes['Data'] ?? []);
        $categories = array_values(array_filter($allCats, fn($c) => $c->type === 'expense'));

        return view('cashflow.upcoming_bills', compact('bills', 'summary', 'month', 'status', 'categories'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'CashflowEntry/POST_CashflowEntry_SaveUpdateDelete', [
            'action'          => 'save',
            'user_id'         => 1,
            'type'            => 'bill',
            'category_id'     => $request->input('category_id'),
            'amount'          => $request->input('amount'),
            'expected_date'   => $request->input('expected_date'),
            'is_recurring'    => $request->input('is_recurring') ? 1 : 0,
            'recurrence_rule' => $request->input('recurrence_rule'),
            'notes'           => $request->input('notes'),
        ]);

        return response()->json($response);
    }

    public function updateStatus(Request $request)
    {
        $response = $this->callApi('POST', 'CashflowEntry/POST_CashflowEntry_SaveUpdateDelete', [
            'action' => 'update_status',
            'id'     => $request->input('id'),
            'status' => $request->input('status', 'realised'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'CashflowEntry/POST_CashflowEntry_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

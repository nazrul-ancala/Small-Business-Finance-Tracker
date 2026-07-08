<?php

namespace App\Http\Controllers\cashflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageExpectedIncomeController extends Controller
{
    public function index(Request $request)
    {
        $month  = $request->input('month', '');
        $status = $request->input('status', '');

        $params = ['type' => 'income', 'month' => $month];
        if ($status) $params['status'] = $status;

        $incomeRes = $this->callApi('GET', 'CashflowEntry/GET_CashflowEntryList', $params);
        $incomes   = array_map(fn($i) => (object) $i, $incomeRes['Data'] ?? []);

        $total    = array_sum(array_map(fn($i) => $i->amount, $incomes));
        $realised = array_sum(array_map(
            fn($i) => $i->status === 'realised' ? $i->amount : 0,
            $incomes
        ));
        $remaining = $total - $realised;

        $summary = ['total' => $total, 'realised' => $realised, 'remaining' => $remaining];

        $catRes     = $this->callApi('GET', 'Category/GET_CategoryList');
        $allCats    = array_map(fn($c) => (object) $c, $catRes['Data'] ?? []);
        $categories = array_values(array_filter($allCats, fn($c) => $c->type === 'income'));

        return view('cashflow.expected_income', compact('incomes', 'summary', 'month', 'status', 'categories'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'CashflowEntry/POST_CashflowEntry_SaveUpdateDelete', [
            'action'          => 'save',
            'user_id'         => 1,
            'type'            => 'income',
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

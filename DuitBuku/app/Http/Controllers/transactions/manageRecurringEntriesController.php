<?php

namespace App\Http\Controllers\transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageRecurringEntriesController extends Controller
{
    public function index(Request $request)
    {
        $type      = $request->input('type');
        $frequency = $request->input('frequency');

        $params = [];
        if ($type)      $params['type']      = $type;
        if ($frequency) $params['frequency'] = $frequency;

        $response  = $this->callApi('GET', 'RecurringEntry/GET_RecurringEntryList', $params);
        $recurring = array_map(fn($r) => (object) $r, $response['Data'] ?? []);

        $catResponse = $this->callApi('GET', 'Category/GET_CategoryList');
        $allCats     = array_map(fn($c) => (object) $c, $catResponse['Data'] ?? []);
        $incomeCats  = array_values(array_filter($allCats, fn($c) => $c->type === 'income'));
        $expenseCats = array_values(array_filter($allCats, fn($c) => $c->type === 'expense'));

        return view('transactions.recurring_entries', compact('recurring', 'type', 'frequency', 'incomeCats', 'expenseCats'));
    }

    public function save(Request $request)
    {
        $startDate = $request->input('start_date');

        if ($startDate && str_contains($startDate, '/')) {
            [$d, $m, $y] = explode('/', $startDate);
            $startDate = "$y-$m-$d";
        }

        $response = $this->callApi('POST', 'RecurringEntry/POST_RecurringEntry_SaveUpdateDelete', [
            'action'      => 'save',
            'user_id'     => 1,
            'description' => $request->input('description'),
            'type'        => $request->input('type'),
            'category'    => $request->input('category'),
            'amount'      => $request->input('amount'),
            'frequency'   => $request->input('frequency'),
            'start_date'  => $startDate,
        ]);

        return response()->json($response);
    }

    public function apply(Request $request)
    {
        $id = $request->input('id');

        $entry = $this->callApi('GET', 'RecurringEntry/GET_SpecificRecurringEntry', ['id' => $id]);
        if (! ($entry['Success'] ?? false)) {
            return response()->json(['Success' => false, 'Message' => 'Recurring entry not found.']);
        }
        $data = $entry['Data'];

        $txnResponse = $this->callApi('POST', 'Transaction/POST_Transaction_SaveUpdateDelete', [
            'action'   => 'save',
            'user_id'  => $data['user_id'] ?? 1,
            'category' => $data['category'],
            'type'     => $data['type'],
            'amount'   => $data['amount'],
            'note'     => $data['description'],
            'date'     => date('Y-m-d'),
        ]);

        if (! ($txnResponse['Success'] ?? false)) {
            return response()->json($txnResponse);
        }

        $this->callApi('POST', 'RecurringEntry/POST_RecurringEntry_SaveUpdateDelete', [
            'action' => 'bump_next',
            'id'     => $id,
        ]);

        return response()->json(['Success' => true, 'Message' => 'Transaction created and next date updated.']);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'RecurringEntry/POST_RecurringEntry_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

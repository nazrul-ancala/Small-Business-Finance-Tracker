<?php

namespace App\Http\Controllers\transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageAllTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $month    = $request->input('month', date('Y-m'));
        $category = $request->input('category');

        $params = ['month' => $month];
        if ($category) {
            $params['category'] = $category;
        }

        $response     = $this->callApi('GET', 'Transaction/GET_TransactionList', $params);
        $transactions = array_map(fn($t) => (object) $t, $response['Data'] ?? []);

        $catResponse = $this->callApi('GET', 'Category/GET_CategoryList');
        $allCats     = array_map(fn($c) => (object) $c, $catResponse['Data'] ?? []);
        $incomeCats  = array_values(array_filter($allCats, fn($c) => $c->type === 'income'));
        $expenseCats = array_values(array_filter($allCats, fn($c) => $c->type === 'expense'));

        return view('transactions.all_transactions', compact('transactions', 'month', 'category', 'allCats', 'incomeCats', 'expenseCats'));
    }

    public function save(Request $request)
    {
        $date     = $request->input('date');
        $amount   = $request->input('amount');
        $category = $request->input('category');
        $type     = $request->input('type');
        $note     = $request->input('note');

        // Convert DD/MM/YYYY → YYYY-MM-DD
        if ($date && str_contains($date, '/')) {
            [$d, $m, $y] = explode('/', $date);
            $date = "$y-$m-$d";
        }

        $response = $this->callApi('POST', 'Transaction/POST_Transaction_SaveUpdateDelete', [
            'action'   => 'save',
            'user_id'  => 1,
            'category' => $category,
            'type'     => $type,
            'amount'   => $amount,
            'note'     => $note,
            'date'     => $date,
        ]);

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $date = $request->input('date');

        if ($date && str_contains($date, '/')) {
            [$d, $m, $y] = explode('/', $date);
            $date = "$y-$m-$d";
        }

        $response = $this->callApi('POST', 'Transaction/POST_Transaction_SaveUpdateDelete', [
            'action'   => 'update',
            'id'       => $request->input('id'),
            'category' => $request->input('category'),
            'type'     => $request->input('type'),
            'amount'   => $request->input('amount'),
            'note'     => $request->input('note'),
            'date'     => $date,
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'Transaction/POST_Transaction_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

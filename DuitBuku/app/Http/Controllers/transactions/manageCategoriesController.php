<?php

namespace App\Http\Controllers\transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');

        $params = [];
        if ($type) $params['type'] = $type;

        $response   = $this->callApi('GET', 'Category/GET_CategoryList', $params);
        $categories = array_map(fn($c) => (object) $c, $response['Data'] ?? []);

        return view('transactions.categories', compact('categories', 'type'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'Category/POST_Category_SaveUpdateDelete', [
            'action' => 'save',
            'name'   => $request->input('name'),
            'type'   => $request->input('type'),
            'color'  => $request->input('color', '#4f6ef7'),
            'icon'   => $request->input('icon', 'ti-tag'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'Category/POST_Category_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

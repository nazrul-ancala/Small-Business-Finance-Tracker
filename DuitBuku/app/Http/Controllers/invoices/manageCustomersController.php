<?php

namespace App\Http\Controllers\invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageCustomersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $params = [];
        if ($search) $params['search'] = $search;

        $response  = $this->callApi('GET', 'Customer/GET_CustomerList', $params);
        $customers = array_map(fn($c) => (object) $c, $response['Data'] ?? []);

        return view('invoices.customers', compact('customers', 'search'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'Customer/POST_Customer_SaveUpdateDelete', [
            'action'       => 'save',
            'user_id'      => 1,
            'name'         => $request->input('name'),
            'company_name' => $request->input('company_name'),
            'email'        => $request->input('email'),
            'phone'        => $request->input('phone'),
            'address'      => $request->input('address'),
        ]);

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = $this->callApi('POST', 'Customer/POST_Customer_SaveUpdateDelete', [
            'action'       => 'update',
            'id'           => $request->input('id'),
            'name'         => $request->input('name'),
            'company_name' => $request->input('company_name'),
            'email'        => $request->input('email'),
            'phone'        => $request->input('phone'),
            'address'      => $request->input('address'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'Customer/POST_Customer_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

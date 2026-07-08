<?php

namespace App\Http\Controllers\invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageAllInvoicesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $month  = $request->input('month', date('Y-m'));

        $params = ['month' => $month];
        if ($status) $params['status'] = $status;

        $invResponse = $this->callApi('GET', 'Invoice/GET_InvoiceList', $params);
        $invoices    = array_map(fn($i) => (object) $i, $invResponse['Data'] ?? []);

        $custResponse = $this->callApi('GET', 'Customer/GET_CustomerList');
        $customers    = array_map(fn($c) => (object) $c, $custResponse['Data'] ?? []);

        $summary = [
            'total'       => count($invoices),
            'paid'        => count(array_filter($invoices, fn($i) => $i->status === 'paid')),
            'outstanding' => array_sum(array_map(
                fn($i) => $i->status !== 'paid' ? ($i->grand_total - $i->amount_paid) : 0,
                $invoices
            )),
            'overdue'     => count(array_filter($invoices, fn($i) => $i->status === 'overdue')),
        ];

        return view('invoices.all_invoices', compact('invoices', 'customers', 'summary', 'status', 'month'));
    }

    public function save(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number') ?: 'INV-' . date('ymdHis');

        $response = $this->callApi('POST', 'Invoice/POST_Invoice_SaveUpdateDelete', [
            'action'         => 'save',
            'user_id'        => 1,
            'customer_id'    => $request->input('customer_id'),
            'invoice_number' => $invoiceNumber,
            'issue_date'     => $request->input('issue_date'),
            'due_date'       => $request->input('due_date'),
            'notes'          => $request->input('notes'),
            'subtotal'       => $request->input('subtotal', 0),
            'tax_percent'    => $request->input('tax_percent', 0),
            'discount'       => $request->input('discount', 0),
            'grand_total'    => $request->input('grand_total', 0),
            'status'         => $request->input('status', 'draft'),
            'items'          => $request->input('items', '[]'),
        ]);

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = $this->callApi('POST', 'Invoice/POST_Invoice_SaveUpdateDelete', [
            'action'         => 'update',
            'id'             => $request->input('id'),
            'customer_id'    => $request->input('customer_id'),
            'invoice_number' => $request->input('invoice_number'),
            'issue_date'     => $request->input('issue_date'),
            'due_date'       => $request->input('due_date'),
            'notes'          => $request->input('notes'),
            'subtotal'       => $request->input('subtotal'),
            'tax_percent'    => $request->input('tax_percent'),
            'discount'       => $request->input('discount'),
            'grand_total'    => $request->input('grand_total'),
            'items'          => $request->input('items', '[]'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'Invoice/POST_Invoice_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }

    public function updateStatus(Request $request)
    {
        $response = $this->callApi('POST', 'Invoice/POST_Invoice_SaveUpdateDelete', [
            'action' => 'update_status',
            'id'     => $request->input('id'),
            'status' => $request->input('status'),
        ]);

        return response()->json($response);
    }
}

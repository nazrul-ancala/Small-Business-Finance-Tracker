<?php

namespace App\Http\Controllers\invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class managePaymentRecordsController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m'));

        $payResponse = $this->callApi('GET', 'PaymentRecord/GET_PaymentRecordList', ['month' => $month]);
        $payments    = array_map(fn($p) => (object) $p, $payResponse['Data'] ?? []);

        $invResponse  = $this->callApi('GET', 'Invoice/GET_InvoiceList');
        $allInvoices  = array_map(fn($i) => (object) $i, $invResponse['Data'] ?? []);

        // Only sent/partial invoices for the Record Payment dropdown
        $invoices = array_values(array_filter(
            $allInvoices,
            fn($i) => in_array($i->status, ['sent', 'partial'])
        ));

        $pendingCount = count(array_filter(
            $allInvoices,
            fn($i) => in_array($i->status, ['sent', 'partial', 'overdue'])
        ));

        $summary = [
            'total_collected' => array_sum(array_map(fn($p) => $p->amount, $payments)),
            'this_month'      => array_sum(array_map(fn($p) => $p->amount, $payments)),
            'pending_count'   => $pendingCount,
        ];

        return view('invoices.payment_records', compact('payments', 'invoices', 'summary', 'month'));
    }

    public function save(Request $request)
    {
        $response = $this->callApi('POST', 'PaymentRecord/POST_PaymentRecord_SaveUpdateDelete', [
            'action'         => 'save',
            'invoice_id'     => $request->input('invoice_id'),
            'amount'         => $request->input('amount'),
            'payment_method' => $request->input('payment_method'),
            'paid_at'        => $request->input('paid_at'),
            'notes'          => $request->input('notes'),
        ]);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = $this->callApi('POST', 'PaymentRecord/POST_PaymentRecord_SaveUpdateDelete', [
            'action' => 'delete',
            'id'     => $request->input('id'),
        ]);

        return response()->json($response);
    }
}

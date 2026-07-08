<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function GET_InvoiceList(Request $request): JsonResponse
    {
        $status = $request->input('status');
        $month  = $request->input('month');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Invoice_CRUD(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, null, $month, null, null, null, null, null, null, $status]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Invoice list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_InvoiceList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve invoices.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificInvoice(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo = DB::connection()->getPdo();

            $stmt = $pdo->prepare('CALL sp_Invoice_CRUD(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null, null, null, null, null]);
            $invoice = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $invoice) {
                return $this->fail('Invoice not found.', 404);
            }

            $stmt->closeCursor();

            $stmt2 = $pdo->prepare('CALL sp_InvoiceItem_CRUD(?,?,?,?,?,?,?)');
            $stmt2->execute(['GET_BY_INVOICE', null, $id, null, null, null, null]);
            $items = $stmt2->fetchAll(\PDO::FETCH_OBJ);

            $invoice->items = $items;

            return $this->ok($invoice, 'Invoice retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificInvoice', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve invoice.', 500, $e->getMessage());
        }
    }

    public function POST_Invoice_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'          => $this->saveInvoice($request),
                'update'        => $this->updateInvoice($request),
                'update_status' => $this->updateStatus($request),
                'delete'        => $this->deleteInvoice($request),
                default         => $this->fail('Invalid action. Use: save, update, update_status, or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_Invoice_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveInvoice(Request $request): JsonResponse
    {
        $customerId    = $request->input('customer_id');
        $invoiceNumber = $request->input('invoice_number');
        $issueDate     = $request->input('issue_date');
        $dueDate       = $request->input('due_date');

        if (! $customerId || ! $invoiceNumber || ! $issueDate || ! $dueDate) {
            return $this->fail('customer_id, invoice_number, issue_date and due_date are required.', 400);
        }

        $row = $this->callInvoiceCrud('INSERT', [
            null,
            $request->input('user_id', 1),
            $customerId,
            $invoiceNumber,
            $issueDate,
            $dueDate,
            $request->input('notes'),
            $request->input('subtotal', 0),
            $request->input('tax_percent', 0),
            $request->input('discount', 0),
            $request->input('grand_total', 0),
            $request->input('status', 'draft'),
        ]);

        if (! ($row && $row->Status === 'true')) {
            return $this->fail($row->Message ?? 'Failed to save invoice.', 400);
        }

        $invoiceId = $row->NewId;

        // Save line items
        $itemsJson = $request->input('items', '[]');
        $items     = is_array($itemsJson) ? $itemsJson : json_decode($itemsJson, true);

        if (is_array($items)) {
            $pdo = DB::connection()->getPdo();
            foreach ($items as $item) {
                $stmt = $pdo->prepare('CALL sp_InvoiceItem_CRUD(?,?,?,?,?,?,?)');
                $stmt->execute([
                    'INSERT', null, $invoiceId,
                    $item['description'] ?? '',
                    $item['qty']         ?? 1,
                    $item['unit_price']  ?? 0,
                    $item['total']       ?? 0,
                ]);
                $stmt->closeCursor();
            }
        }

        return $this->ok(['id' => $invoiceId], $row->Message ?? 'Invoice saved.');
    }

    private function updateInvoice(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callInvoiceCrud('UPDATE', [
            $id,
            null,
            $request->input('customer_id'),
            $request->input('invoice_number'),
            $request->input('issue_date'),
            $request->input('due_date'),
            $request->input('notes'),
            $request->input('subtotal'),
            $request->input('tax_percent'),
            $request->input('discount'),
            $request->input('grand_total'),
            null,
        ]);

        if (! ($row && $row->Status === 'true')) {
            return $this->fail($row->Message ?? 'Failed to update invoice.', 400);
        }

        // Replace items: delete all then re-insert
        $itemsJson = $request->input('items');
        if ($itemsJson !== null) {
            $items = is_array($itemsJson) ? $itemsJson : json_decode($itemsJson, true);
            $pdo   = DB::connection()->getPdo();

            $del = $pdo->prepare('CALL sp_InvoiceItem_CRUD(?,?,?,?,?,?,?)');
            $del->execute(['DELETE_BY_INVOICE', null, $id, null, null, null, null]);
            $del->closeCursor();

            if (is_array($items)) {
                foreach ($items as $item) {
                    $ins = $pdo->prepare('CALL sp_InvoiceItem_CRUD(?,?,?,?,?,?,?)');
                    $ins->execute([
                        'INSERT', null, $id,
                        $item['description'] ?? '',
                        $item['qty']         ?? 1,
                        $item['unit_price']  ?? 0,
                        $item['total']       ?? 0,
                    ]);
                    $ins->closeCursor();
                }
            }
        }

        return $this->ok(null, $row->Message ?? 'Invoice updated.');
    }

    private function updateStatus(Request $request): JsonResponse
    {
        $id     = $request->input('id');
        $status = $request->input('status');

        if (! $id || ! $status) {
            return $this->fail('id and status are required.', 400);
        }

        $row = $this->callInvoiceCrud('UPDATE_STATUS', [
            $id, null, null, null, null, null, null, null, null, null, null, $status,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Status updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update status.', 400);
    }

    private function deleteInvoice(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $pdo = DB::connection()->getPdo();

        // Delete items first
        $del = $pdo->prepare('CALL sp_InvoiceItem_CRUD(?,?,?,?,?,?,?)');
        $del->execute(['DELETE_BY_INVOICE', null, $id, null, null, null, null]);
        $del->closeCursor();

        $row = $this->callInvoiceCrud('DELETE', [$id, null, null, null, null, null, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Invoice deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete invoice.', 400);
    }

    private function callInvoiceCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_Invoice_CRUD(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}

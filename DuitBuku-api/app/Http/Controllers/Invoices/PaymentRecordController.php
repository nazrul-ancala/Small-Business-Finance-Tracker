<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentRecordController extends Controller
{
    public function GET_PaymentRecordList(Request $request): JsonResponse
    {
        $month = $request->input('month');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_PaymentRecord_CRUD(?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, null, null, null, $month]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Payment record list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_PaymentRecordList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve payment records.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificPaymentRecord(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_PaymentRecord_CRUD(?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Payment record not found.', 404);
            }

            return $this->ok($row, 'Payment record retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificPaymentRecord', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve payment record.', 500, $e->getMessage());
        }
    }

    public function POST_PaymentRecord_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'   => $this->savePayment($request),
                'delete' => $this->deletePayment($request),
                default  => $this->fail('Invalid action. Use: save or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_PaymentRecord_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function savePayment(Request $request): JsonResponse
    {
        $invoiceId     = $request->input('invoice_id');
        $amount        = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        $paidAt        = $request->input('paid_at');

        if (! $invoiceId || ! $amount || ! $paymentMethod || ! $paidAt) {
            return $this->fail('invoice_id, amount, payment_method and paid_at are required.', 400);
        }

        $row = $this->callCrud('INSERT', [
            null, $invoiceId, $amount, $paymentMethod, $paidAt, $request->input('notes'), null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Payment recorded.');
        }

        return $this->fail($row->Message ?? 'Failed to record payment.', 400);
    }

    private function deletePayment(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [$id, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Payment deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete payment.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_PaymentRecord_CRUD(?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}

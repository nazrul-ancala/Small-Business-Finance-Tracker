<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashflowEntryController extends Controller
{
    public function GET_CashflowEntryList(Request $request): JsonResponse
    {
        $type   = $request->input('type');
        $month  = $request->input('month');
        $status = $request->input('status');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_CashflowEntry_CRUD(?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, $type, null, null, null, null, null, $status, $month, null]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Cashflow entry list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_CashflowEntryList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve cashflow entries.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificCashflowEntry(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_CashflowEntry_CRUD(?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Entry not found.', 404);
            }

            return $this->ok($row, 'Entry retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificCashflowEntry', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve entry.', 500, $e->getMessage());
        }
    }

    public function POST_CashflowEntry_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'          => $this->saveEntry($request),
                'update_status' => $this->updateStatus($request),
                'delete'        => $this->deleteEntry($request),
                default         => $this->fail('Invalid action. Use: save, update_status or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_CashflowEntry_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveEntry(Request $request): JsonResponse
    {
        $type         = $request->input('type');
        $amount       = $request->input('amount');
        $expectedDate = $request->input('expected_date');

        if (! $type || ! $amount || ! $expectedDate) {
            return $this->fail('type, amount and expected_date are required.', 400);
        }

        $isRecurring = $request->input('is_recurring') ? 1 : 0;

        $row = $this->callCrud('INSERT', [
            null,
            $request->input('user_id', 1),
            $type,
            $request->input('category_id'),
            $amount,
            $expectedDate,
            $isRecurring ? $request->input('recurrence_rule', 'monthly') : null,
            $request->input('notes'),
            null,
            null,
            $isRecurring,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Entry saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save entry.', 400);
    }

    private function updateStatus(Request $request): JsonResponse
    {
        $id     = $request->input('id');
        $status = $request->input('status');

        if (! $id || ! $status) {
            return $this->fail('id and status are required.', 400);
        }

        $row = $this->callCrud('UPDATE_STATUS', [
            $id, null, null, null, null, null, null, null, $status, null, null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Status updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update status.', 400);
    }

    private function deleteEntry(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [
            $id, null, null, null, null, null, null, null, null, null, null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Entry deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete entry.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_CashflowEntry_CRUD(?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}

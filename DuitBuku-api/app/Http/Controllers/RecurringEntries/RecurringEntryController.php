<?php

namespace App\Http\Controllers\RecurringEntries;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecurringEntryController extends Controller
{
    public function GET_RecurringEntryList(Request $request): JsonResponse
    {
        $type      = $request->input('type');
        $frequency = $request->input('frequency');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_RecurringEntry_CRUD(?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, $type, null, null, $frequency, null, null]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Recurring entry list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_RecurringEntryList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve recurring entries.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificRecurringEntry(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_RecurringEntry_CRUD(?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Recurring entry not found.', 404);
            }

            return $this->ok($row, 'Recurring entry retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificRecurringEntry', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve recurring entry.', 500, $e->getMessage());
        }
    }

    public function POST_RecurringEntry_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'      => $this->saveEntry($request),
                'update'    => $this->updateEntry($request),
                'delete'    => $this->deleteEntry($request),
                'bump_next' => $this->bumpNext($request),
                'update_status' => $this->updateStatus($request),
                default  => $this->fail('Invalid action. Use: save, update, delete, bump_next, or update_status.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_RecurringEntry_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveEntry(Request $request): JsonResponse
    {
        $userId      = $request->input('user_id');
        $description = $request->input('description');
        $type        = $request->input('type');
        $category    = $request->input('category');
        $amount      = $request->input('amount');
        $frequency   = $request->input('frequency');
        $startDate   = $request->input('start_date');

        if (! $userId || ! $description || ! $type || ! $category || ! $amount || ! $frequency || ! $startDate) {
            return $this->fail('user_id, description, type, category, amount, frequency and start_date are required.', 400);
        }

        $row = $this->callCrud('INSERT', [null, $userId, $description, $type, $category, $amount, $frequency, $startDate, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Recurring entry saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save recurring entry.', 400);
    }

    private function updateEntry(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('UPDATE', [$id, null,
            $request->input('description'),
            $request->input('type'),
            $request->input('category'),
            $request->input('amount'),
            $request->input('frequency'),
            null,
            null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Recurring entry updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update recurring entry.', 400);
    }

    private function deleteEntry(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [$id, null, null, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Recurring entry deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete recurring entry.', 400);
    }

    private function bumpNext(Request $request): JsonResponse
    {
        $id = $request->input('id');
        if (! $id) return $this->fail('id is required.', 400);

        $row = $this->callCrud('BUMP_NEXT', [$id, null, null, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Next date bumped.');
        }
        return $this->fail($row->Message ?? 'Failed to bump next date.', 400);
    }

    private function updateStatus(Request $request): JsonResponse
    {
        $id     = $request->input('id');
        $status = $request->input('status');

        if (! $id || ! $status) {
            return $this->fail('id and status are required.', 400);
        }

        $row = $this->callCrud('UPDATE_STATUS', [$id, null, null, null, null, null, null, null, $status]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Status updated.');
        }
        return $this->fail($row->Message ?? 'Failed to update status.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_RecurringEntry_CRUD(?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
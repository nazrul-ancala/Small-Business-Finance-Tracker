<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function GET_TransactionList(Request $request): JsonResponse
    {
        $month    = $request->input('month');
        $category = $request->input('category');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Transaction_CRUD(?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, null, null, null, null, $month, $category]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Transaction list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_TransactionList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve transactions.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificTransaction(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Transaction_CRUD(?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Transaction not found.', 404);
            }

            return $this->ok($row, 'Transaction retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificTransaction', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve transaction.', 500, $e->getMessage());
        }
    }

    public function POST_Transaction_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'   => $this->saveTransaction($request),
                'update' => $this->updateTransaction($request),
                'delete' => $this->deleteTransaction($request),
                default  => $this->fail('Invalid action. Use: save, update, or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_Transaction_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveTransaction(Request $request): JsonResponse
    {
        $userId   = $request->input('user_id');
        $category = $request->input('category');
        $type     = $request->input('type');
        $amount   = $request->input('amount');
        $note     = $request->input('note');
        $date     = $request->input('date');

        if (! $userId || ! $category || ! $type || ! $amount || ! $date) {
            return $this->fail('user_id, category, type, amount and date are required.', 400);
        }

        $row = $this->callCrud('INSERT', [null, $userId, $category, $type, $amount, $note, $date, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Transaction saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save transaction.', 400);
    }

    private function updateTransaction(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('UPDATE', [$id, null,
            $request->input('category'),
            $request->input('type'),
            $request->input('amount'),
            $request->input('note'),
            $request->input('date'),
            null, null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Transaction updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update transaction.', 400);
    }

    private function deleteTransaction(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [$id, null, null, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Transaction deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete transaction.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_Transaction_CRUD(?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
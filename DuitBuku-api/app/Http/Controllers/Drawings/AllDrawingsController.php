<?php

namespace App\Http\Controllers\Drawings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AllDrawingsController extends Controller
{
    public function GET_DrawingList(Request $request): JsonResponse
    {
        $month = $request->input('month');
        $type  = $request->input('type');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Drawing_CRUD(?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, $type, null, null, null, $month]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Drawing list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_DrawingList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve drawings.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificDrawing(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Drawing_CRUD(?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Drawing not found.', 404);
            }

            return $this->ok($row, 'Drawing retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificDrawing', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve drawing.', 500, $e->getMessage());
        }
    }

    public function POST_Drawing_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'   => $this->saveDrawing($request),
                'delete' => $this->deleteDrawing($request),
                default  => $this->fail('Invalid action. Use: save or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_Drawing_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveDrawing(Request $request): JsonResponse
    {
        $drawnAt     = $request->input('drawn_at');
        $type        = $request->input('type');
        $description = $request->input('description');
        $amount      = $request->input('amount');

        if (! $drawnAt || ! $type || ! $description || ! $amount) {
            return $this->fail('drawn_at, type, description and amount are required.', 400);
        }

        $row = $this->callCrud('INSERT', [
            null,
            $request->input('user_id', 1),
            $drawnAt,
            $type,
            $description,
            $amount,
            $request->input('notes'),
            null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Drawing saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save drawing.', 400);
    }

    private function deleteDrawing(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [
            $id, null, null, null, null, null, null, null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Drawing deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete drawing.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_Drawing_CRUD(?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}

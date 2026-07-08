<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function GET_CategoryList(Request $request): JsonResponse
    {
        $type = $request->input('type');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Category_CRUD(?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, $type, null, null]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Category list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_CategoryList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve categories.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificCategory(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Category_CRUD(?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Category not found.', 404);
            }

            return $this->ok($row, 'Category retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificCategory', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve category.', 500, $e->getMessage());
        }
    }

    public function POST_Category_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'   => $this->saveCategory($request),
                'update' => $this->updateCategory($request),
                'delete' => $this->deleteCategory($request),
                default  => $this->fail('Invalid action. Use: save, update, or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_Category_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function saveCategory(Request $request): JsonResponse
    {
        $name = $request->input('name');
        $type = $request->input('type');

        if (! $name || ! $type) {
            return $this->fail('name and type are required.', 400);
        }

        $row = $this->callCrud('INSERT', [null, $name, $type, $request->input('color'), $request->input('icon')]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Category saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save category.', 400);
    }

    private function updateCategory(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('UPDATE', [$id,
            $request->input('name'),
            $request->input('type'),
            $request->input('color'),
            $request->input('icon'),
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Category updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update category.', 400);
    }

    private function deleteCategory(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [$id, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Category deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete category.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_Category_CRUD(?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
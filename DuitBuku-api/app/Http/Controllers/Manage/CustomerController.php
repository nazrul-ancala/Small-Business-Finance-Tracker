<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function GET_CustomerList(Request $request): JsonResponse
    {
        $search = $request->input('search');

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Customer_CRUD(?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_ALL', null, null, null, null, null, null, null, $search]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Customer list retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_CustomerList', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve customers.', 500, $e->getMessage());
        }
    }

    public function GET_SpecificCustomer(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Customer_CRUD(?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_BY_ID', $id, null, null, null, null, null, null, null]);
            $row  = $stmt->fetch(\PDO::FETCH_OBJ);

            if (! $row) {
                return $this->fail('Customer not found.', 404);
            }

            return $this->ok($row, 'Customer retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SpecificCustomer', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve customer.', 500, $e->getMessage());
        }
    }

    public function POST_Customer_SaveUpdateDelete(Request $request): JsonResponse
    {
        $action = strtolower($request->input('action', ''));

        if (! $action) {
            return $this->fail('action is required.', 400);
        }

        try {
            return match ($action) {
                'save'   => $this->insertCustomer($request),
                'update' => $this->updateCustomer($request),
                'delete' => $this->deleteCustomer($request),
                default  => $this->fail('Invalid action. Use: save, update, or delete.', 400),
            };
        } catch (\Throwable $e) {
            Log::error('POST_Customer_SaveUpdateDelete', ['action' => $action, 'error' => $e->getMessage()]);
            return $this->fail('Operation failed.', 500, $e->getMessage());
        }
    }

    private function insertCustomer(Request $request): JsonResponse
    {
        $name = $request->input('name');

        if (! $name) {
            return $this->fail('name is required.', 400);
        }

        $row = $this->callCrud('INSERT', [
            null,
            $request->input('user_id', 1),
            $name,
            $request->input('company_name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('address'),
            null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(['id' => $row->NewId ?? null], $row->Message ?? 'Customer saved.');
        }

        return $this->fail($row->Message ?? 'Failed to save customer.', 400);
    }

    private function updateCustomer(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('UPDATE', [
            $id,
            null,
            $request->input('name'),
            $request->input('company_name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('address'),
            null,
        ]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Customer updated.');
        }

        return $this->fail($row->Message ?? 'Failed to update customer.', 400);
    }

    private function deleteCustomer(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if (! $id) {
            return $this->fail('id is required.', 400);
        }

        $row = $this->callCrud('DELETE', [$id, null, null, null, null, null, null, null]);

        if ($row && $row->Status === 'true') {
            return $this->ok(null, $row->Message ?? 'Customer deleted.');
        }

        return $this->fail($row->Message ?? 'Failed to delete customer.', 400);
    }

    private function callCrud(string $action, array $params): mixed
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_Customer_CRUD(?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}

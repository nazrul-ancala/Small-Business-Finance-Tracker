<?php

namespace App\Http\Controllers\Drawings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalarySummaryController extends Controller
{
    public function GET_SalarySummary(Request $request): JsonResponse
    {
        $year = $request->input('year', date('Y'));

        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_Drawing_CRUD(?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['GET_SALARY_SUMMARY', null, null, null, null, null, null, null, $year]);
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $this->ok($rows, 'Salary summary retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_SalarySummary', ['error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve salary summary.', 500, $e->getMessage());
        }
    }
}

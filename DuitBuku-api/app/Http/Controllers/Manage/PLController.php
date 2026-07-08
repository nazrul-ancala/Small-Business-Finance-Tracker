<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PLController extends Controller
{
    public function GET_PLMonth(Request $request): JsonResponse
    {
        $month = $request->input('month');

        if (! $month) {
            return $this->fail('month is required.', 400);
        }

        try {
            $rows = $this->callCrud('GET_MONTH', [null, null, $month]);

            return $this->ok($rows, 'P&L month report retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_PLMonth', ['month' => $month, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve P&L month report.', 500, $e->getMessage());
        }
    }

    public function GET_PLQuarterly(Request $request): JsonResponse
    {
        $year    = $request->input('year');
        $quarter = $request->input('quarter');

        if (! $year || ! $quarter) {
            return $this->fail('year and quarter are required.', 400);
        }

        try {
            $rows = $this->callCrud('GET_QUARTERLY', [$year, $quarter, null]);

            return $this->ok($rows, 'P&L quarterly report retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_PLQuarterly', ['year' => $year, 'quarter' => $quarter, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve P&L quarterly report.', 500, $e->getMessage());
        }
    }

    public function GET_PLYearly(Request $request): JsonResponse
    {
        $year = $request->input('year');

        if (! $year) {
            return $this->fail('year is required.', 400);
        }

        try {
            $rows = $this->callCrud('GET_YEARLY', [$year, null, null]);

            return $this->ok($rows, 'P&L yearly report retrieved successfully.');
        } catch (\Throwable $e) {
            Log::error('GET_PLYearly', ['year' => $year, 'error' => $e->getMessage()]);
            return $this->fail('Failed to retrieve P&L yearly report.', 500, $e->getMessage());
        }
    }

    private function callCrud(string $action, array $params): array
    {
        $pdo  = DB::connection()->getPdo();
        $stmt = $pdo->prepare('CALL sp_PL_Report(?,?,?,?)');
        $stmt->execute(array_merge([$action], $params));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}

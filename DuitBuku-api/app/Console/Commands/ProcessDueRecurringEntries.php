<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessDueRecurringEntries extends Command
{
    protected $signature = 'recurring:process-due';

    protected $description = 'Create transactions for active recurring entries whose next_date has arrived, and advance their next_date';

    public function handle(): int
    {
        try {
            $pdo  = DB::connection()->getPdo();
            $stmt = $pdo->prepare('CALL sp_RecurringEntry_CRUD(?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute(['PROCESS_DUE', null, null, null, null, null, null, null, null, null]);
            $row = $stmt->fetch(\PDO::FETCH_OBJ);

            if ($row && $row->Status === 'true') {
                $this->info($row->Message ?? 'Due recurring entries processed.');
                Log::info('recurring:process-due', ['message' => $row->Message ?? null]);
                return self::SUCCESS;
            }

            $this->error($row->Message ?? 'Failed to process due recurring entries.');
            Log::error('recurring:process-due', ['message' => $row->Message ?? null]);
            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            Log::error('recurring:process-due', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }
}

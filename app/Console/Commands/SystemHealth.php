<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemHealth extends Command
{
    protected $signature = 'system:health {--fix : Attempt to fix issues}';
    protected $description = 'Check system health status';

    public function handle()
    {
        $this->info('=== Restaurant QR System Health Check ===\n');

        $checks = [
            'Database' => $this->checkDatabase(),
            'Storage' => $this->checkStorage(),
            'Cache' => $this->checkCache(),
            'Orders Table' => $this->checkOrdersTable(),
        ];

        $allPassed = true;

        foreach ($checks as $name => $result) {
            $status = $result['status'] ? '✓' : '✗';
            $color = $result['status'] ? 'info' : 'error';
            
            $this->$color("{$status} {$name}: {$result['message']}");
            
            if (!$result['status']) {
                $allPassed = false;
            }
        }

        $this->newLine();

        if ($allPassed) {
            $this->info('✓ All systems operational');
            return Command::SUCCESS;
        }

        $this->error('✗ Some systems need attention');
        return Command::FAILURE;
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => true, 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        try {
            $path = storage_path('app');
            $writable = is_writable($path);
            $free = disk_free_space($path);
            $freeGB = round($free / 1024 / 1024 / 1024, 2);

            return [
                'status' => $writable && $freeGB > 1,
                'message' => $writable ? "{$freeGB} GB free" : 'Not writable'
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            Cache::put('health_check', 'ok', 60);
            $value = Cache::get('health_check');
            
            return [
                'status' => $value === 'ok',
                'message' => $value === 'ok' ? 'Working' : 'Failed'
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function checkOrdersTable(): array
    {
        try {
            $count = \App\Models\Order::count();
            return ['status' => true, 'message' => "{$count} orders in system"];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Table error'];
        }
    }
}

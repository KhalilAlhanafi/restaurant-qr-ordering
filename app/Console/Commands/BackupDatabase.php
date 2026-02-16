<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--path= : Custom backup path}';
    protected $description = 'Backup the restaurant database';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "restaurant_backup_{$timestamp}.sql";
        
        $backupPath = $this->option('path') ?? storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $fullPath = $backupPath . '/' . $filename;

        // Build mysqldump command
        $command = sprintf(
            'mysqldump -h %s -u %s %s %s > %s',
            escapeshellarg($host),
            escapeshellarg($username),
            $password ? '-p' . escapeshellarg($password) : '',
            escapeshellarg($database),
            escapeshellarg($fullPath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            // Compress the backup
            $zipPath = $fullPath . '.gz';
            exec("gzip -c {$fullPath} > {$zipPath}");
            unlink($fullPath);

            // Keep only last 7 backups
            $this->cleanupOldBackups($backupPath);

            $this->info("✓ Database backup created: {$zipPath}");
            
            // Log success
            logger()->info('Database backup completed', [
                'file' => $zipPath,
                'size' => filesize($zipPath)
            ]);

            return Command::SUCCESS;
        }

        $this->error('✗ Database backup failed');
        logger()->error('Database backup failed', ['code' => $returnCode]);
        
        return Command::FAILURE;
    }

    private function cleanupOldBackups(string $path): void
    {
        $files = glob($path . '/*.sql.gz');
        
        if (count($files) <= 7) {
            return;
        }

        // Sort by modification time (oldest first)
        usort($files, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        // Delete oldest files, keeping 7 most recent
        $filesToDelete = array_slice($files, 0, count($files) - 7);
        
        foreach ($filesToDelete as $file) {
            unlink($file);
            logger()->info('Old backup deleted', ['file' => $file]);
        }
    }
}

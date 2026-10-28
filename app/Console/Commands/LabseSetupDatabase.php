<?php

namespace App\Console\Commands;

use App\Services\MaterializedViewService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class LabseSetupDatabase extends Command
{
    protected $signature = 'labse:setup {--refresh-only : Hanya refresh materialized views}';
    protected $description = 'Setup tabel PostgreSQL, Stored Procedures, dan Materialized Views untuk Lab SE';

    public function handle(MaterializedViewService $mvService): int
    {
        if ($this->option('refresh-only')) {
            $this->info('Me-refresh Materialized Views...');
            $ok = $mvService->refreshViewsComplete();
            $this->info($ok ? 'Materialized Views berhasil di-refresh!' : 'Gagal me-refresh Materialized Views (pastikan MV sudah dibuat).');
            return self::SUCCESS;
        }

        $sqlFiles = [
            'database.sql' => database_path('sql/database.sql'),
            'stored_procedures.sql' => database_path('sql/stored_procedures.sql'),
            'materialized_views_complete.sql' => database_path('sql/materialized_views_complete.sql'),
        ];

        foreach ($sqlFiles as $label => $path) {
            if (!file_exists($path)) {
                $this->warn("File SQL tidak ditemukan: {$path}");
                continue;
            }
            $this->info("Menjalankan {$label}...");
            try {
                $sql = file_get_contents($path);
                // Hilangkan perintah meta psql seperti \c lab_se jika ada
                $sql = preg_replace('/^\\\\c\s+.*$/m', '', $sql);
                // CREATE DATABASE tidak diizinkan pada provider terkelola (Supabase/Neon):
                // database sudah dibuat oleh provider, jadi perintah ini harus dibuang
                // agar sisa file (CREATE TABLE) tetap dijalankan.
                $sql = preg_replace('/^\s*CREATE\s+DATABASE\b[^;]*;/im', '', $sql);
                DB::unprepared($sql);
                $this->info("Berhasil menjalankan {$label}");
            } catch (Throwable $e) {
                $this->warn("Peringatan saat menjalankan {$label}: " . $e->getMessage());
            }
        }

        $mvService->refreshViewsComplete();
        $this->info('Setup database Lab SE selesai!');
        return self::SUCCESS;
    }
}

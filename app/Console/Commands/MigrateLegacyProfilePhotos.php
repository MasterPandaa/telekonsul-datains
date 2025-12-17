<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateLegacyProfilePhotos extends Command
{
    protected $signature = 'profilephoto:migrate-legacy
                            {--dry-run : Tampilkan perubahan tanpa menulis file/DB}
                            {--delete-source : Hapus file sumber di public/img/profil setelah berhasil dipindahkan}';

    protected $description = 'Pindahkan foto profil legacy dari public/img/profil ke storage/app/public/profil dan update path di database.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $deleteSource = (bool) $this->option('delete-source');

        $legacyRoot = public_path('img/profil');

        if (!is_dir($legacyRoot)) {
            $this->info("Tidak ada folder legacy: {$legacyRoot}");
            $this->updateDatabasePaths($dryRun);
            return self::SUCCESS;
        }

        $this->info('Scanning legacy photos in: ' . $legacyRoot);

        $moved = 0;
        $skipped = 0;
        $errors = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($legacyRoot, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo || !$file->isFile()) {
                continue;
            }

            $absolutePath = $file->getPathname();
            $relativeFromLegacy = str_replace('\\', '/', Str::after($absolutePath, $legacyRoot));
            $relativeFromLegacy = ltrim($relativeFromLegacy, '/');

            // Expecting: {userId}/fotoprofil.ext
            $parts = explode('/', $relativeFromLegacy);
            if (count($parts) < 2) {
                $skipped++;
                continue;
            }

            $userId = $parts[0];
            if (!ctype_digit((string) $userId)) {
                $skipped++;
                continue;
            }

            $filename = $parts[count($parts) - 1];
            if (!Str::startsWith(strtolower($filename), 'fotoprofil.')) {
                $skipped++;
                continue;
            }

            $targetRelative = 'profil/' . $userId . '/' . $filename;

            if (Storage::disk('public')->exists($targetRelative)) {
                $skipped++;
                continue;
            }

            $this->line(($dryRun ? '[DRY-RUN] ' : '') . "Copy {$absolutePath} -> storage/public/{$targetRelative}");

            try {
                if (!$dryRun) {
                    $contents = file_get_contents($absolutePath);
                    if ($contents === false) {
                        throw new \RuntimeException('Gagal membaca file');
                    }

                    Storage::disk('public')->put($targetRelative, $contents);

                    if ($deleteSource) {
                        @unlink($absolutePath);
                    }
                }

                $moved++;
            } catch (\Throwable $e) {
                $errors++;
                $this->error("Gagal memindahkan {$absolutePath}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('File migrate summary:');
        $this->info("- moved: {$moved}");
        $this->info("- skipped: {$skipped}");
        $this->info("- errors: {$errors}");

        $this->newLine();
        $this->updateDatabasePaths($dryRun);

        $this->newLine();
        $this->info('Selesai.');

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function updateDatabasePaths(bool $dryRun): void
    {
        $tables = [
            ['table' => 'pasiens', 'column' => 'foto'],
            ['table' => 'dokters', 'column' => 'foto'],
            ['table' => 'dosens', 'column' => 'foto'],
        ];

        foreach ($tables as $spec) {
            $table = $spec['table'];
            $column = $spec['column'];

            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            $count = DB::table($table)
                ->whereNotNull($column)
                ->where(function ($q) use ($column) {
                    $q->where($column, 'like', 'img/profil/%')
                      ->orWhere($column, 'like', '/img/profil/%');
                })
                ->count();

            $this->info(($dryRun ? '[DRY-RUN] ' : '') . "DB {$table}.{$column}: {$count} baris perlu update");

            if ($count === 0 || $dryRun) {
                continue;
            }

            $rows = DB::table($table)
                ->select('id', $column)
                ->whereNotNull($column)
                ->where(function ($q) use ($column) {
                    $q->where($column, 'like', 'img/profil/%')
                      ->orWhere($column, 'like', '/img/profil/%');
                })
                ->get();

            foreach ($rows as $row) {
                $current = (string) $row->{$column};
                $normalized = ltrim($current, '/');
                $updated = Str::replaceFirst('img/profil/', 'storage/profil/', $normalized);

                DB::table($table)
                    ->where('id', $row->id)
                    ->update([$column => $updated]);
            }
        }
    }
}

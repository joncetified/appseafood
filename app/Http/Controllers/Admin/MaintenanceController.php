<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemBackup;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(): View
    {
        return view('admin.maintenance.index', [
            'backups' => SystemBackup::query()->with('creator')->latest()->paginate(10),
        ]);
    }

    public function backupDatabase(Request $request, BackupService $backupService): RedirectResponse
    {
        $tables = collect(Schema::getTableListing())
            ->reject(fn (string $table) => in_array($table, ['cache', 'jobs', 'job_batches', 'failed_jobs', 'migrations', 'sessions'], true))
            ->values();

        $payload = [
            'database' => config('database.default'),
            'generated_at' => now()->toIso8601String(),
            'tables' => $tables,
            'data' => $tables->mapWithKeys(fn (string $table) => [$table => DB::table($table)->get()])->all(),
        ];

        $backupService->createJsonBackup(
            'database',
            'Full database backup',
            $payload,
            $request->user()?->id,
        );

        return redirect()->route('admin.maintenance.index')->with('status', 'Backup database berhasil dibuat.');
    }

    public function restartDatabase(): RedirectResponse
    {
        DB::disconnect();
        DB::purge(config('database.default'));
        DB::reconnect(config('database.default'));

        Artisan::call('optimize:clear');

        return redirect()->route('admin.maintenance.index')->with('status', 'Koneksi database dan cache Laravel berhasil direfresh.');
    }

    public function download(SystemBackup $backup): StreamedResponse
    {
        abort_unless($backup->file_path && Storage::disk('local')->exists($backup->file_path), 404, 'File backup tidak ditemukan.');

        return Storage::disk('local')->download($backup->file_path);
    }
}

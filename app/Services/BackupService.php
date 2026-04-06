<?php

namespace App\Services;

use App\Models\SystemBackup;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public function createJsonBackup(string $type, string $label, array $payload, ?int $createdBy = null): SystemBackup
    {
        $fileName = 'backups/'.$type.'-'.now()->format('Ymd-His').'.json';

        Storage::disk('local')->put($fileName, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return SystemBackup::create([
            'type' => $type,
            'label' => $label,
            'file_path' => $fileName,
            'status' => 'completed',
            'metadata' => [
                'generated_at' => now()->toDateTimeString(),
                'records' => collect($payload['data'] ?? [])->count(),
            ],
            'created_by' => $createdBy,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Role;
use App\Models\SeafoodItem;
use App\Services\BackupService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ImportExportController extends Controller
{
    public function index(): View
    {
        return view('admin.import-export.index');
    }

    public function exportUsers(): Response
    {
        $rows = User::query()
            ->with('role')
            ->orderBy('name')
            ->get()
            ->map(function (User $user) {
                return [
                    $user->name,
                    $user->email,
                    $user->whatsapp_number,
                    $user->role?->name,
                    $user->is_active ? '1' : '0',
                    optional($user->email_verified_at)->toDateTimeString(),
                ];
            });

        return $this->csvResponse(
            'users-export-'.now()->format('Ymd-His').'.csv',
            ['name', 'email', 'whatsapp_number', 'role', 'is_active', 'email_verified_at'],
            $rows->all(),
        );
    }

    public function exportItems(): Response
    {
        $rows = SeafoodItem::query()
            ->with('category')
            ->orderBy('name')
            ->get()
            ->map(function (SeafoodItem $item) {
                return [
                    $item->category?->name,
                    $item->name,
                    $item->description,
                    (string) $item->price,
                    $item->image_path,
                    (string) $item->spicy_level,
                    $item->is_available ? '1' : '0',
                    $item->is_featured ? '1' : '0',
                ];
            });

        return $this->csvResponse(
            'items-export-'.now()->format('Ymd-His').'.csv',
            ['category', 'name', 'description', 'price', 'image_path', 'spicy_level', 'is_available', 'is_featured'],
            $rows->all(),
        );
    }

    public function backupUsers(Request $request, BackupService $backupService): RedirectResponse
    {
        $backupService->createJsonBackup(
            'users',
            'Users backup',
            [
                'generated_at' => now()->toIso8601String(),
                'data' => User::query()->with('role')->get()->toArray(),
            ],
            $request->user()?->id,
        );

        return redirect()->route('admin.import-export.index')->with('status', 'Backup users berhasil dibuat.');
    }

    public function backupItems(Request $request, BackupService $backupService): RedirectResponse
    {
        $backupService->createJsonBackup(
            'items',
            'Items backup',
            [
                'generated_at' => now()->toIso8601String(),
                'data' => SeafoodItem::query()->with('category')->get()->toArray(),
            ],
            $request->user()?->id,
        );

        return redirect()->route('admin.import-export.index')->with('status', 'Backup items berhasil dibuat.');
    }

    public function importUsers(Request $request): RedirectResponse
    {
        $request->validate([
            'users_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $rows = $this->readCsv($request->file('users_file')->getRealPath());
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($headers, $row);

            if (! $data || empty($data['email'])) {
                continue;
            }

            $role = Role::query()->where('name', $data['role'] ?? 'pelanggan')->first();

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'] ?? $data['email'],
                    'whatsapp_number' => $data['whatsapp_number'] ?: null,
                    'role_id' => $role?->id,
                    'is_active' => ($data['is_active'] ?? '1') === '1',
                    'email_verified_at' => ! empty($data['email_verified_at']) ? $data['email_verified_at'] : now(),
                    'password' => 'ChangeMe123!',
                ],
            );
        }

        return redirect()->route('admin.import-export.index')->with('status', 'Import users selesai.');
    }

    public function importItems(Request $request): RedirectResponse
    {
        $request->validate([
            'items_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $rows = $this->readCsv($request->file('items_file')->getRealPath());
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($headers, $row);

            if (! $data || empty($data['name'])) {
                continue;
            }

            $categoryName = trim((string) ($data['category'] ?? 'Uncategorized'));
            $category = Category::query()->firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'description' => null, 'is_active' => true],
            );

            SeafoodItem::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'] ?: null,
                    'price' => (float) ($data['price'] ?? 0),
                    'image_path' => $data['image_path'] ?: null,
                    'spicy_level' => (int) ($data['spicy_level'] ?? 0),
                    'is_available' => ($data['is_available'] ?? '1') === '1',
                    'is_featured' => ($data['is_featured'] ?? '0') === '1',
                ],
            );
        }

        return redirect()->route('admin.import-export.index')->with('status', 'Import items selesai.');
    }

    private function csvResponse(string $fileName, array $headers, array $rows): Response
    {
        $content = collect([$headers, ...$rows])
            ->map(fn (array $row) => collect($row)->map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"')->implode(','))
            ->implode("\r\n");

        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'rb');

        if (! $handle) {
            return $rows;
        }

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }
}

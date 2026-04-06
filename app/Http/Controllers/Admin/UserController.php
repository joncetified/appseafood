<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()
                ->with(['role', 'creator', 'updater'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => new User(),
            'roles' => Role::orderBy('label')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => $request->boolean('is_active', true) ? now() : null,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => Role::orderBy('label')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($this->wouldDisableLastSuperAdmin($user, (int) $validated['role_id'], $request->boolean('is_active', true))) {
            return back()->withErrors([
                'role_id' => 'Minimal harus ada satu super admin aktif di sistem.',
            ])->withInput();
        }

        $payload = [
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        if ($payload['is_active'] && ! $user->email_verified_at) {
            $payload['email_verified_at'] = now();
        }

        $user->update($payload);

        return redirect()->route('admin.users.index')->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($this->wouldDisableLastSuperAdmin($user, null, false)) {
            return back()->withErrors([
                'email' => 'Minimal harus ada satu super admin aktif di sistem.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User berhasil dihapus.');
    }

    private function wouldDisableLastSuperAdmin(User $user, ?int $newRoleId, bool $isActive): bool
    {
        $superAdminRoleId = Role::query()->where('name', 'super_admin')->value('id');

        if (! $superAdminRoleId || $user->role_id !== $superAdminRoleId) {
            return false;
        }

        $keepingSameRole = $newRoleId === null || $newRoleId === $superAdminRoleId;
        $keepingActive = $isActive;

        if ($keepingSameRole && $keepingActive) {
            return false;
        }

        return User::query()
            ->where('role_id', $superAdminRoleId)
            ->where('is_active', true)
            ->count() <= 1;
    }
}

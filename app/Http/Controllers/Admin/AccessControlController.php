<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PagePermission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessControlController extends Controller
{
    public function index(): View
    {
        return view('admin.access-control.index', [
            'roles' => Role::query()->with('pagePermissions')->orderBy('label')->get(),
            'permissions' => PagePermission::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin') {
            return back()->withErrors([
                'permissions' => 'Hak akses Super Admin dikunci dan selalu penuh.',
            ]);
        }

        $validated = $request->validate([
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', 'exists:page_permissions,id'],
        ]);

        $role->pagePermissions()->sync($validated['permission_ids'] ?? []);

        return redirect()->route('admin.access-control.index')->with('status', 'Hak akses role berhasil diperbarui.');
    }
}

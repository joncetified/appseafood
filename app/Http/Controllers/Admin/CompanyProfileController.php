<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.company-profile.edit', [
            'profile' => CompanyProfile::firstOrNew(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'weekday_hours' => ['nullable', 'string', 'max:255'],
            'weekend_hours' => ['nullable', 'string', 'max:255'],
        ]);

        $profile = CompanyProfile::firstOrNew();
        $profile->fill($validated);
        $profile->save();

        return redirect()->route('admin.company-profile.edit')->with('status', 'Profil perusahaan berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SeafoodItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeafoodItemController extends Controller
{
    public function index(): View
    {
        return view('admin.seafood-items.index', [
            'items' => SeafoodItem::query()
                ->with(['category', 'creator', 'updater'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.seafood-items.form', [
            'item' => new SeafoodItem(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_path' => ['nullable', 'string', 'max:2048'],
            'spicy_level' => ['nullable', 'integer', 'min:0', 'max:5'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        SeafoodItem::create([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.Str::lower(Str::random(4)),
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'spicy_level' => (int) ($validated['spicy_level'] ?? 0),
        ]);

        return redirect()->route('admin.seafood-items.index')->with('status', 'Menu seafood berhasil dibuat.');
    }

    public function edit(SeafoodItem $seafoodItem): View
    {
        return view('admin.seafood-items.form', [
            'item' => $seafoodItem,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SeafoodItem $seafoodItem): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_path' => ['nullable', 'string', 'max:2048'],
            'spicy_level' => ['nullable', 'integer', 'min:0', 'max:5'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $seafoodItem->update([
            ...$validated,
            'slug' => Str::slug($validated['name']).'-'.$seafoodItem->id,
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
            'spicy_level' => (int) ($validated['spicy_level'] ?? 0),
        ]);

        return redirect()->route('admin.seafood-items.index')->with('status', 'Menu seafood berhasil diperbarui.');
    }

    public function destroy(SeafoodItem $seafoodItem): RedirectResponse
    {
        $seafoodItem->delete();

        return redirect()->route('admin.seafood-items.index')->with('status', 'Menu seafood berhasil dihapus.');
    }
}

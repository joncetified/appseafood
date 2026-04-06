<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Promotion;
use App\Models\SeafoodItem;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $menuItems = SeafoodItem::with('category:id,name,slug')
            ->where('is_available', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get()
            ->map(fn (SeafoodItem $item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description ?? '',
                'price' => (float) $item->price,
                'image_url' => $item->image_url,
                'category' => $item->category?->slug ?? 'uncategorized',
                'category_name' => $item->category?->name,
                'badge' => $item->is_featured ? 'Featured' : null,
                'popular' => $item->is_featured,
                'spicy' => $item->spicy_level > 0,
                'rating' => null,
            ]);

        $promotions = Promotion::where('is_active', true)
            ->orderByDesc('start_date')
            ->get(['id', 'title', 'description']);

        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->get(['id', 'customer_name', 'rating', 'content']);

        $profile = CompanyProfile::query()->first();

        return view('app', [
            'appData' => [
                'categories' => $categories,
                'menuItems' => $menuItems,
                'promotions' => $promotions,
                'testimonials' => $testimonials,
                'profile' => $profile,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\SeafoodItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'categories' => Category::count(),
                'menu_items' => SeafoodItem::count(),
                'orders' => Order::count(),
            ],
            'incomeSummary' => [
                'today' => (float) Order::query()->whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount'),
                'yesterday' => (float) Order::query()->whereDate('created_at', $today->copy()->subDay())->where('payment_status', 'paid')->sum('total_amount'),
                'this_month' => (float) Order::query()->whereBetween('created_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->where('payment_status', 'paid')->sum('total_amount'),
                'last_month' => (float) Order::query()->whereBetween('created_at', [$today->copy()->subMonthNoOverflow()->startOfMonth(), $today->copy()->subMonthNoOverflow()->endOfMonth()])->where('payment_status', 'paid')->sum('total_amount'),
            ],
            'showIncomeSummary' => auth()->user()?->hasRole(['super_admin', 'manager']) ?? false,
            'recentOrders' => Order::query()
                ->with(['creator', 'updater'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}

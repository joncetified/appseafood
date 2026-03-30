<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\SeafoodItem;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'categories' => Category::count(),
                'menu_items' => SeafoodItem::count(),
                'orders' => Order::count(),
            ],
            'recentOrders' => Order::latest()->take(5)->get(),
        ]);
    }
}

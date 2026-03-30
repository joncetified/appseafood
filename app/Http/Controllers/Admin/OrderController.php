<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::with('items')->latest()->paginate(10),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'payment_status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Pesanan berhasil diperbarui.');
    }
}

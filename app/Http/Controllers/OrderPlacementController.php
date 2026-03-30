<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SeafoodItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderPlacementController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:seafood_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $result = DB::transaction(function () use ($validated, $request) {
            $items = collect($validated['items']);
            $seafoodItems = SeafoodItem::whereIn('id', $items->pluck('id'))->get()->keyBy('id');

            $subtotal = $items->sum(function (array $item) use ($seafoodItems) {
                $seafoodItem = $seafoodItems->get($item['id']);

                return $seafoodItem ? ((float) $seafoodItem->price * $item['quantity']) : 0;
            });

            $tax = round($subtotal * 0.1, 2);
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => 'ORD-'.now()->format('YmdHis'),
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total_amount' => $total,
            ]);

            foreach ($items as $item) {
                $seafoodItem = $seafoodItems->get($item['id']);

                if (! $seafoodItem) {
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'seafood_item_id' => $seafoodItem->id,
                    'item_name' => $seafoodItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $seafoodItem->price,
                    'line_total' => (float) $seafoodItem->price * $item['quantity'],
                ]);
            }

            return $order;
        });

        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'order_number' => $result->order_number,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch 'confirmed' or 'delivered' orders and eager-load items for the modal
        $orders = Order::with(['user', 'items.product'])
            ->whereIn('status', ['confirmed', 'delivered'])
            ->latest()
            ->get();
        return view('warehouse.orders.index', compact('orders'));
    }

    public function deliver(Order $order)
    {
        // Prevent action if order is not in a 'confirmed' state
        if ($order->status !== 'confirmed') {
            return redirect()->route('warehouse.orders.index')->with('error', 'This order cannot be delivered.');
        }

        try {
            DB::transaction(function () use ($order) {
                // 1. Loop through each item in the order and deduct from inventory
                foreach ($order->items as $item) {
                    // Find the product and decrement its inventory
                    // This uses a lock to prevent race conditions
                    $product = $item->product()->lockForUpdate()->first();
                    $product->decrement('inventory', $item->quantity);
                }

                // 2. Update the order status to 'delivered'
                $order->update([
                    'status' => 'delivered',
                    'delivered_by' => Auth::id(),
                    'delivered_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            // If anything goes wrong, redirect back with an error
            return redirect()->route('warehouse.orders.index')->with('error', 'Could not process delivery. Error: ' . $e->getMessage());
        }

        return redirect()->route('warehouse.orders.index')->with('success', __('Order marked as delivered and inventory updated.'));
    }

    public function show(Order $order)
    {
        // Ensure we only show confirmed orders and load related items
        if ($order->status !== 'confirmed') {
            abort(404);
        }

        $order->load('user', 'orderItems.product');

        return view('warehouse.orders.show', compact('order'));
    }
}

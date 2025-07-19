<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

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
        // Update the order with status, deliverer ID, and delivery timestamp
        $order->update([
            'status' => 'delivered',
            'delivered_by' => Auth::id(),
            'delivered_at' => now(),
        ]);

        return redirect()->route('warehouse.orders.index')->with('success', __('Order marked as delivered.'));
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

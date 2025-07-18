<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch only 'confirmed' orders
        $orders = Order::with('user')->where('status', 'confirmed')->latest()->get();
        return view('warehouse.orders.index', compact('orders'));
    }

    public function deliver(Order $order)
    {
        // Update the order status to 'delivered'
        $order->update(['status' => 'delivered']);
        return redirect()->route('warehouse.orders.index')->with('success', __('Order marked as delivered.'));
    }
}
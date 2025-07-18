<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Eager load the 'user' relationship to prevent N+1 query problems
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Confirm an order.
     */
    public function confirm(Order $order)
    {
        // Update the order status to 'confirmed'
        $order->update(['status' => 'confirmed']);

        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', __('Order confirmed successfully.'));
    }
}

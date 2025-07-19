<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Eager load all necessary relationships: user, items with their products, and the deliverer
        $orders = Order::with(['user', 'items.product', 'deliverer'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Confirm an order.
     */
    public function confirm(Order $order)
    {
        // Update the order with status, confirmer ID, and confirmation timestamp
        $order->update([
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', __('Order confirmed successfully.'));
    }
}

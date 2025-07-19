<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        // FIX: Should be 'orders' (plural)
        $orders = Auth::user()->orders()->with('items.product')->latest()->get(); // 
        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty!'));
        }

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($user, $cart) {
            // FIX: Should be 'orders' (plural)
            $order = $user->orders()->create([ // 
                'total_price' => $cart->items->sum(fn($item) => $item->quantity * $item->product->price),
                'status' => 'pending', // Status is pending confirmation by admin
            ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            // Clear the user's cart
            $cart->items()->delete();
            $cart->delete();
        });

        return redirect()->route('orders.index')->with('success', __('Your order has been placed successfully!'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Display the contents of the shopping cart.
     */
    public function index()
    {
        // Get the current user's cart and eager load items and their associated products
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        $totalPrice = 0;
        if ($cart) {
            // Calculate the total price of all items in the cart
            $totalPrice = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
        }

        // Return the cart view with the cart data and total price
        return view('cart.index', compact('cart', 'totalPrice'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        // Find or create a cart for the currently authenticated user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Check if the product is already in the cart
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // If it exists, just increment the quantity by 1
            $cartItem->increment('quantity');
        } else {
            // Otherwise, create a new item in the cart with quantity 1
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', __('Product added to cart successfully!'));
    }
}

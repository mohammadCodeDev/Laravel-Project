<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = Auth::user();

        // Find or create a cart for the user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if the item is already in the cart
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // If it exists, increment the quantity
            $cartItem->increment('quantity');
        } else {
            // Otherwise, create a new cart item
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('products.index')->with('success', __('Product added to cart successfully!'));
    }
}

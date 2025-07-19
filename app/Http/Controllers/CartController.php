<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        $totalPrice = 0;
        if ($cart) {
            $totalPrice = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);
        }
        return view('cart.index', compact('cart', 'totalPrice'));
    }
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $cart->items()->create(['product_id' => $request->product_id, 'quantity' => 1]);
        }
        return redirect()->back()->with('success', __('Product added to cart successfully!'));
    }

    /**
     * Update the specified item in the cart.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Authorization: Ensure the user owns the cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->action == 'increase') {
            $cartItem->increment('quantity');
        } elseif ($request->action == 'decrease') {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                // If quantity is 1, remove the item completely
                $cartItem->delete();
            }
        }

        return redirect()->route('cart.index')->with('success', __('Cart updated successfully!'));
    }

    /**
     * Remove the specified item from the cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Authorization: Ensure the user owns the cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', __('Item removed from cart successfully!'));
    }
}
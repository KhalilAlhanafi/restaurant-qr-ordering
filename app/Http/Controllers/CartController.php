<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get current cart from session
     */
    public function index()
    {
        $cart = session('cart', []);
        return response()->json(['cart' => $cart]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = Item::findOrFail($validated['item_id']);
        
        $cart = session('cart', []);
        
        // Check if item already exists in cart
        $existingIndex = null;
        foreach ($cart as $index => $cartItem) {
            if ($cartItem['id'] == $validated['item_id']) {
                $existingIndex = $index;
                break;
            }
        }
        
        if ($existingIndex !== null) {
            // Update existing item quantity
            $cart[$existingIndex]['quantity'] += $validated['quantity'];
        } else {
            // Add new item to cart
            $cart[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'show_price' => $item->show_price,
                'quantity' => $validated['quantity']
            ];
        }
        
        session(['cart' => $cart]);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'message' => 'Item added to cart'
        ]);
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session('cart', []);
        
        foreach ($cart as $index => $cartItem) {
            if ($cartItem['id'] == $validated['item_id']) {
                if ($validated['quantity'] == 0) {
                    // Remove item if quantity is 0
                    unset($cart[$index]);
                    $cart = array_values($cart); // Re-index array
                } else {
                    // Update quantity
                    $cart[$index]['quantity'] = $validated['quantity'];
                }
                break;
            }
        }
        
        session(['cart' => $cart]);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'message' => 'Cart updated'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $cart = session('cart', []);
        
        $cart = array_filter($cart, function($item) use ($validated) {
            return $item['id'] != $validated['item_id'];
        });
        
        $cart = array_values($cart); // Re-index array
        session(['cart' => $cart]);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session(['cart' => []]);
        
        return response()->json([
            'success' => true,
            'cart' => [],
            'message' => 'Cart cleared'
        ]);
    }

    /**
     * Get cart summary (total items and price)
     */
    public function summary()
    {
        $cart = session('cart', []);
        
        $totalItems = 0;
        $totalPrice = 0;
        
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
            if ($item['show_price']) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
        }
        
        return response()->json([
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'cart' => $cart
        ]);
    }
}

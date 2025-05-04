<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('visitors.cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->images[0]->path ?? null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Success added to cart',
            'cart_count' => count($cart)
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $quantity = $request->input('quantity');

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $quantity); // Tidak bisa kurang dari 1
            session(['cart' => $cart]);

            $itemTotal = $cart[$id]['quantity'] * $cart[$id]['price'];
            $cartTotal = array_sum(array_map(fn($i) => $i['quantity'] * $i['price'], $cart));

            return response()->json([
                'success' => true,
                'item_total' => number_format($itemTotal, 0, ',', '.'),
                'cart_total' => number_format($cartTotal, 0, ',', '.'),
                'quantity' => $cart[$id]['quantity']
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'message' => 'Deleted from cart.',
            'cart_count' => count($cart),
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }
}

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
        $quantityToAdd = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        $existingQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        $totalRequestedQty = $existingQty + $quantityToAdd;

        // ❗ Cek stok
        if ($totalRequestedQty > $product->stock) {
            return response()->json([
                'message' => 'Stok tidak mencukupi. Tersisa ' . $product->stock . ' item.',
                'success' => false,
            ], 400);
        }

        // Lanjut menambah ke cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $totalRequestedQty;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantityToAdd,
                'image' => $product->images[0]->path ?? null,
                'note' => '',
            ];
        }

        session()->put('cart', $cart);
        return response()->json([
            'message' => 'Success added to cart',
            'cart_count' => count($cart),
            'success' => true,
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan di cart']);
        }

        $product = Products::findOrFail($id);

        // ❗ Cek stok
        if ($quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Maksimal: ' . $product->stock,
            ]);
        }

        $cart[$id]['quantity'] = max(1, $quantity);
        session(['cart' => $cart]);

        $itemTotal = $cart[$id]['quantity'] * $cart[$id]['price'];
        $cartTotal = array_sum(array_map(fn($i) => $i['quantity'] * $i['price'], $cart));

        return response()->json([
            'success' => true,
            'item_total' => number_format($itemTotal, 0, ',', '.'),
            'cart_total' => number_format($cartTotal, 0, ',', '.'),
            'quantity' => $cart[$id]['quantity'],
        ]);
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

    public function updateNote(Request $request, $id)
    {
        $cart = session('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di cart']);
        }

        $cart[$id]['note'] = $request->note;
        session(['cart' => $cart]);

        return response()->json(['success' => true, 'message' => 'Catatan diperbarui']);
    }
}

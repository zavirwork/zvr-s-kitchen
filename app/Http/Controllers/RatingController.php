<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak memberi rating untuk pesanan ini.');
        }

        if ($order->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa memberi rating untuk pesanan yang selesai.');
        }

        if ($order->rating) {
            return back()->with('error', 'Anda sudah memberi rating untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500',
        ]);

        Rating::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

        return back()->with('success', 'Thank you!');
    }

    // In your UserOrdersController or wherever you handle order details
    public function show($id)
    {
        $order = Order::with(['items.product', 'rating'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'message' => $order->message,
            'total_price' => $order->total_price,
            'created_at' => $order->created_at,
            'items' => $order->items->map(function ($item) {
                return [
                    'product' => $item->product ? ['name' => $item->product->name] : null,
                    'quantity' => $item->quantity,
                    'price_at_time' => $item->price_at_time,
                ];
            }),
            'rating' => $order->rating ? [
                'id' => $order->rating->id,
                'rating' => $order->rating->rating,
                'review' => $order->rating->review,
                'created_at' => $order->rating->created_at
            ] : null
        ]);
    }
}

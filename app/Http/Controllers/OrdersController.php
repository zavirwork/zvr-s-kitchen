<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.order.index', compact('orders'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        // Validasi status yang dikirim
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,completed,cancelled',
        ]);

        // Cari pesanan berdasarkan ID
        $order = Order::findOrFail($orderId);

        // Update status pesanan
        $order->status = $request->status;
        $order->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.orders.index')->with('success', 'Order status successfully updated!');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return response()->json($order);
    }
}

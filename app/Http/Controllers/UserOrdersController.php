<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->with('items.product')
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'items.product',
            'items.addons'
        ])->where('user_id', Auth::id())->findOrFail($id);

        return response()->json($order);
    }
}

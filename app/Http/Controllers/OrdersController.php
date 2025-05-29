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
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,completed,cancelled',
        ]);

        $order = Order::findOrFail($orderId);

        $order->status = $request->status;
        $order->save();

        // Kirim pesan via Fonnte
        $apiKey = env('FONNTE_API_KEY');
        $phone = $order->customer_whatsapp;
        $name = $order->customer_name;
        $status = ucfirst($order->status);

        $message = "Halo {$name},\n\nStatus pesanan Anda (ID: #{$order->id}) telah berubah menjadi *{$status}*.\nTerima kasih telah berbelanja di toko kami!";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $phone,
                'message' => $message,
                'delay' => 2,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $apiKey,
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            dd('Fonnte error: ' . curl_error($curl));
        }

        curl_close($curl);
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated and WhatsApp notification sent!');
    }


    public function show(Order $order)
    {
        $order->load('items.product');
        return response()->json($order);
    }
}

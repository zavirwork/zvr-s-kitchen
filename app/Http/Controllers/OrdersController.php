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

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',  // Validasi untuk kolom message
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();  // Mulai transaksi DB
        try {
            // Simpan order beserta pesan tambahan
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'message' => $request->message ?? '-',
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $itemsData = [];
            $messageItems = "";
            $totalPrice = 0;

            foreach ($request->cart as $item) {
                // Lock produk yang dipilih agar tidak ada transaksi lain yang bisa mengubahnya
                $product = Products::where('id', $item['product_id'])
                    ->lockForUpdate()  // Mengunci baris produk ini untuk transaksi ini
                    ->first();

                // Cek apakah stok cukup
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Not enough stock for ' . $product->name], 400);
                }

                // Kurangi stok produk
                $product->stock -= $item['quantity'];
                $product->save();  // Simpan perubahan stok

                // Insert data ke order_items
                $itemsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_time' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Update total harga
                $totalPrice += $product->price * $item['quantity'];

                // Menambahkan item ke dalam pesan Telegram
                $messageItems .= "- {$product->name} x {$item['quantity']} @ Rp{$product->price}\n";
            }

            // Insert order_items ke database
            OrderItem::insert($itemsData);

            // Update total harga di tabel orders
            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();  // Commit transaksi jika semua berhasil

            // Kirim ke Telegram
            $token = config('services.telegram.bot_token');
            $chatId = config('services.telegram.chat_id');

            $message = "*New Order Received*\n\n";
            $message .= "Name: {$order->customer_name}\n";
            $message .= "WhatsApp: {$order->customer_whatsapp}\n";
            $message .= "Order ID: #{$order->id}\n";
            $message .= "Items:\n" . $messageItems;
            $message .= "\nMessage (Optional):\n{$order->message}";
            $message .= "\n\n*Grand Total:* Rp{$order->total_price}";

            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);

            return response()->json(['message' => 'Order successfully placed!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika terjadi kesalahan
            return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
        }
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
        return redirect()->route('index.orders')->with('success', 'Order status successfully updated!');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return response()->json($order);
    }
}

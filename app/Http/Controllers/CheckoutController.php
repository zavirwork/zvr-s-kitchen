<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        return view('visitors.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        // validasi data yang diminta sudah terpenuhi atau belum
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',  // Validasi untuk kolom message
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id', //simpan data product id dan kuantitasnya
            'cart.*.quantity' => 'required|integer|min:1',
            'customer_location' => 'required|string',
            'evidence_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Pisahkan lat dan long
        [$lat, $long] = explode(',', $request->customer_location . ',');
        $path = $request->file('evidence_transfer')->store('evidence_transfers', 'public'); // simpan file bukti transfer

        DB::beginTransaction();  // Mulai transaksi DB > mengunci database sementara (antrian)
        try {

            // Simpan order beserta pesan tambahan
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'message' => $request->message ?? '-',
                'total_price' => 0,
                'status' => 'pending',
                'latitude' => trim($lat),
                'longitude' => trim($long),
                'evidence_transfer' => $path,
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

            // isi pesan telegram
            $message = "*New Order Received*\n\n";
            $message .= "Name: {$order->customer_name}\n";
            $message .= "WhatsApp: {$order->customer_whatsapp}\n";
            $message .= "Order ID: #{$order->id}\n";
            $message .= "Items:\n" . $messageItems;
            $message .= "\nMessage (Optional):\n{$order->message}";
            $message .= "\n\n*Grand Total:* Rp{$order->total_price}";

            //proses mengirim pesan telegram
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);

            session()->forget('cart');
            return redirect('/')->with('success', 'Success, We have received your order');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika terjadi kesalahan
            return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
        }
    }
}

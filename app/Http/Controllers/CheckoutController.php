<?php

namespace App\Http\Controllers;

use App\Models\Addon;
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
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.addons' => 'nullable|array',
            'cart.*.addons.*' => 'exists:addons,id',
            'customer_location' => 'required|string',
            'evidence_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        [$lat, $long] = explode(',', $request->customer_location . ',');
        $path = $request->file('evidence_transfer')->store('evidence_transfers', 'public');

        DB::beginTransaction();
        try {
            $cart = $request->input('cart', []);
            $specialNote = $request->input('message', '');

            $compiledNotes = 'notes: ' . $specialNote . '; ';
            foreach ($cart as $item) {
                $productId = $item['product_id'];
                $product = Products::find($productId);
                $productName = $product ? strtolower($product->name) : 'menu';

                if (!empty($item['note'])) {
                    $compiledNotes .= $productName . ': ' . $item['note'] . '; ';
                }
            }

            $order = Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'message' => $compiledNotes ?? '-',
                'total_price' => 0,
                'status' => 'pending',
                'latitude' => trim($lat),
                'longitude' => trim($long),
                'evidence_transfer' => $path,
            ]);

            $totalPrice = 0;
            $messageItems = "";

            foreach ($cart as $item) {
                $product = Products::find($item['product_id']);

                // ❌ Bagian pengurangan stok DIHILANGKAN

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_time' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $itemTotal = $product->price * $item['quantity'];
                $totalPrice += $itemTotal;
                $messageItems .= "- {$product->name} x {$item['quantity']} @ Rp{$product->price}\n";

                if (isset($item['addons']) && is_array($item['addons'])) {
                    foreach ($item['addons'] as $addonId) {
                        $addon = Addon::find($addonId);
                        if ($addon) {
                            $orderItem->addons()->attach($addon->id, [
                                'price_at_time' => $addon->price,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $totalPrice += $addon->price;
                            $messageItems .= "   + {$addon->name} @ Rp{$addon->price}\n";
                        }
                    }
                }
            }

            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();

            // Telegram Notification
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

            session()->forget('cart');

            return redirect('/')->with('success', 'Success, We have received your order');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

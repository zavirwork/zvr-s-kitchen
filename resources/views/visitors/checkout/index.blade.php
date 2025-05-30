<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zvr's Kitchen - Kebab enak dan bergizi!</title>

    <link rel="shortcut icon" href="{{ asset('asset-visitor/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('assets-visitor/css/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Rubik:wght@400;500;600;700&family=Shadows+Into+Light&display=swap"
        rel="stylesheet">
</head>

<body id="top">
<main>
    <article>
        <section class="section" id="checkout-card" style="padding: 40px 0;">
            <div class="container">
                <div
                    style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 24px;">

                    <h2 style="margin-bottom: 20px; text-align: center;">Checkout</h2>

                    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data"
                          style="max-width: 600px; margin: 0 auto;">
                        @csrf

                        <h2 class="section-title">Customer</h2>

                        <div class="form-group">
                            <input type="hidden" name="user_id" required class="input-field"
                            value="{{ auth()->check() ? auth()->user()->id : '' }}">
                        </div>
                        
                        {{-- Nama --}}
                        <div class="form-group">
                            <label for="customer_name">Name</label>
                            <input type="text" name="customer_name" required class="input-field"
                                   value="{{ auth()->check() ? auth()->user()->name : '' }}" placeholder="John Doe">
                        </div>

                        {{-- WhatsApp --}}
                        <div class="form-group">
                            <label for="customer_whatsapp">WhatsApp</label>
                            <input type="text" name="customer_whatsapp" required class="input-field"
                                   value="{{ auth()->check() ? auth()->user()->user_whatsapp : '' }}" placeholder="628xxxxxxxxxx">
                        </div>

                        {{-- Bukti Transfer --}}
                        <div class="form-group">
                            <label for="evidence_transfer">Upload Bukti Transfer</label>
                            <input type="file" name="evidence_transfer" class="input-field"
                                   accept="image/*,application/pdf" required>
                        </div>

                        {{-- Lokasi --}}
                        <div class="form-group">
                            <label for="customer_location">Lokasi Anda (Otomatis)</label>
                            <input type="text" id="customer_location" readonly name="customer_location"
                                   class="input-field" required>
                        </div>

                        {{-- Catatan --}}
                        <div class="form-group">
                            <label for="message">Note (opsional)</label>
                            <textarea name="message" rows="3" class="input-field"></textarea>
                        </div>

                        <hr style="margin: 2rem 0;">

                        <h3>Order Summary</h3>

                        @if (count($cart) > 0)
                            @foreach ($cart as $index => $item)
                                <div class="cart-item" style="margin-bottom: 1.5rem;">
                                    <div class="item-info">
                                        <span class="item-name">{{ $item['name'] }}</span>
                                        <div class="qty-control">
                                            <span>Qty: {{ $item['quantity'] }}</span>
                                            <span>x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="item-total">
                                            Total: Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <input type="hidden" name="cart[{{ $index }}][product_id]" value="{{ $item['id'] }}">
                                    <input type="hidden" name="cart[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                                    <input type="hidden" name="cart[{{ $index }}][price]" value="{{ $item['price'] }}">
                                </div>
                            @endforeach

                            <h3 style="text-align: right; color: #e74c3c;">Grand Total: Rp
                                {{ number_format($total, 0, ',', '.') }}</h3>
                        @else
                            <p>Keranjang Anda kosong.</p>
                        @endif

                        <button type="submit" class="btn-delete"
                                style="background-color: #ff8c00; color: white; padding: 12px 20px; border: none; border-radius: 6px; font-size: 16px; width: 100%;">
                            Order Now
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </article>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude.toFixed(7);
                const long = position.coords.longitude.toFixed(7);
                document.getElementById("customer_location").value = `${lat}, ${long}`;
            }, function (error) {
                alert("Gagal mendapatkan lokasi. Silakan izinkan akses lokasi pada browser.");
            });
        } else {
            alert("Browser Anda tidak mendukung fitur lokasi.");
        }
    });
</script>

</body>
</html>

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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        :root {
            --primary: #ff8c00;
            --primary-dark: #e67e00;
            --light: #f9f9f9;
            --dark: #333;
            --gray: #777;
            --light-gray: #eee;
            --border-radius: 8px;
            --box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Rubik', sans-serif;
            background-color: #f5f5f5;
            color: var(--dark);
            line-height: 1.6;
        }

        .checkout-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .checkout-header {
            background-color: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .checkout-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .checkout-body {
            padding: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-gray);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-family: 'Rubik', sans-serif;
            font-size: 15px;
            transition: var(--transition);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.2);
        }

        textarea.input-field {
            min-height: 100px;
            resize: vertical;
        }

        .divider {
            height: 1px;
            background-color: var(--light-gray);
            margin: 25px 0;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 500;
            display: block;
            margin-bottom: 5px;
        }

        .qty-control {
            display: flex;
            gap: 10px;
            color: var(--gray);
            font-size: 14px;
        }

        .item-total {
            font-weight: 600;
            color: var(--dark);
        }

        /* Addon styling */
        .addon-wrapper {
            margin-top: 10px;
        }

        .addon-title {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
            color: var(--dark);
        }

        .addon-option {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .addon-option input[type="checkbox"] {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            margin: 0;
        }

        .grand-total {
            text-align: right;
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
            margin: 25px 0;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .bank-info {
            background-color: #f8f8f8;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-top: 5px;
            font-size: 14px;
            color: var(--gray);
        }

        .location-loading {
            color: var(--gray);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .checkout-container {
                margin: 20px;
            }

            .checkout-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body id="top">
    <main>
        <article>
            <section class="section" id="checkout-card" style="padding: 40px 0;">
                <div class="checkout-container">
                    <div class="checkout-header">
                        <h2>Checkout Order</h2>
                    </div>

                    <div class="checkout-body">
                        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="step step-1">
                                <h2 class="section-title">Customer Information</h2>

                                <div class="form-group">
                                    <input type="hidden" name="user_id" required class="input-field"
                                        value="{{ auth()->check() ? auth()->user()->id : '' }}">
                                </div>

                                {{-- Nama --}}
                                <div class="form-group">
                                    <label for="customer_name">Full Name</label>
                                    <input type="text" name="customer_name" required class="input-field"
                                        value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                        placeholder="John Doe">
                                </div>

                                {{-- WhatsApp --}}
                                <div class="form-group">
                                    <label for="customer_whatsapp">WhatsApp Number</label>
                                    <input type="text" name="customer_whatsapp" required class="input-field"
                                        value="{{ auth()->check() ? auth()->user()->user_whatsapp : '' }}"
                                        placeholder="628xxxxxxxxxx">
                                </div>

                                {{-- Lokasi --}}
                                <div class="form-group">
                                    <label for="customer_location">Your Location</label>
                                    <div id="map" style="height: 400px; width: 100%; margin-top: 1rem;"></div>
                                    <br>
                                    <input type="text" id="customer_location" readonly name="customer_location"
                                        class="input-field location-loading" required
                                        value="Detecting your location...">
                                </div>

                                {{-- Detail Lokasi --}}
                                <div class="form-group">
                                    <label for="location_detail">Location Detail</label>
                                    <textarea name="location_detail" rows="3" class="input-field" placeholder="Your location detail"></textarea>
                                </div>

                                {{-- Catatan --}}
                                <div class="form-group">
                                    <label for="message">Special Notes (optional)</label>
                                    <textarea name="message" rows="3" class="input-field" placeholder="Any special requests or delivery instructions"></textarea>
                                </div>
                            </div>

                            <div class="form-group step step-2" style="display:none;">
                                <label for="evidence_transfer">Payment Proof</label>
                                <input type="file" name="evidence_transfer" class="input-field"
                                    accept="image/*" required>
                                <div class="bank-info">
                                    Transfer to: 1247362349 BNI (Mohammad Zavir Zakaria)
                                </div>
                            </div>

                            <div class="divider"></div>

                            <h2 class="section-title">Order Summary</h2>

                            @if (count($cart) > 0)
                                @foreach ($cart as $index => $item)
                                    <div class="cart-item">
                                        {{-- Gambar Produk --}}
                                        @if (!empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}"
                                                alt="{{ $item['name'] }}"
                                                style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px; margin-right: 15px;">
                                        @endif

                                        <div class="item-info">
                                            <span class="item-name">{{ $item['name'] }}</span>
                                            <div class="qty-control">
                                                <span>Qty: {{ $item['quantity'] }}</span>
                                                <span>x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                            </div>
                                            @if (!empty($item['note']))
                                                <span>Note: {{ $item['note'] }}</span>
                                            @endif

                                            {{-- ADDON SECTION START --}}
                                            @php
                                                $product = \App\Models\Products::with('addons')->find($item['id']);
                                            @endphp

                                            @if ($product && $product->addons->count())
                                                <div class="addon-wrapper">
                                                    <strong class="addon-title">Pilih Addon:</strong>
                                                    @foreach ($product->addons as $addon)
                                                        <label class="addon-option">
                                                            <input type="checkbox" class="addon-checkbox"
                                                                data-price="{{ $addon->price }}"
                                                                data-item-index="{{ $index }}"
                                                                name="cart[{{ $index }}][addons][]"
                                                                value="{{ $addon->id }}">
                                                            <span>{{ $addon->name }} (+Rp
                                                                {{ number_format($addon->price, 0, ',', '.') }})</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                            {{-- ADDON SECTION END --}}
                                        </div>

                                        <div class="item-total">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </div>

                                        {{-- Hidden Inputs --}}
                                        <input type="hidden" name="cart[{{ $index }}][product_id]"
                                            value="{{ $item['id'] }}">
                                        <input type="hidden" name="cart[{{ $index }}][quantity]"
                                            value="{{ $item['quantity'] }}">
                                        <input type="hidden" name="cart[{{ $index }}][price]"
                                            value="{{ $item['price'] }}">
                                        <input type="hidden" name="cart[{{ $index }}][note]"
                                            value="{{ $item['note'] ?? '' }}">
                                    </div>
                                @endforeach

                                <div class="grand-total">
                                    Grand Total: <span id="grand-total" data-base-total="{{ $total }}">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <p>Your cart is empty.</p>
                            @endif

                            <div class="step-buttons">
                                <button type="button" class="btn-primary" onclick="nextStep()">Go To
                                    Payment</button>
                                <button type="submit" class="btn-primary" style="display:none;">Submit
                                    Order</button>
                            </div>

                        </form>
                    </div>
                </div>
            </section>
        </article>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const locationField = document.getElementById("customer_location");
            let map, marker;

            function setMarkerAndInput(lat, lng) {
                const formattedLat = parseFloat(lat).toFixed(7);
                const formattedLng = parseFloat(lng).toFixed(7);

                // Set value input
                locationField.value = `${formattedLat}, ${formattedLng}`;
                locationField.classList.remove('location-loading');

                // Buat atau update marker
                if (marker) {
                    marker.setLatLng([formattedLat, formattedLng]);
                } else {
                    marker = L.marker([formattedLat, formattedLng], {
                        draggable: true
                    }).addTo(map);

                    // Jika marker digeser, update input value juga
                    marker.on('dragend', function(e) {
                        const pos = marker.getLatLng();
                        setMarkerAndInput(pos.lat, pos.lng);
                    });
                }
            }

            // Koordinat default (fallback)
            const defaultLat = -6.2000000;
            const defaultLng = 106.8166667;

            // Init map di luar geolocation agar bisa diakses global
            map = L.map('map').setView([defaultLat, defaultLng], 13);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Pakai geolocation jika diizinkan
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    setMarkerAndInput(lat, lng);
                }, function() {
                    setMarkerAndInput(defaultLat, defaultLng);
                });
            } else {
                setMarkerAndInput(defaultLat, defaultLng);
            }

            // Saat peta diklik
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                setMarkerAndInput(lat, lng);
            });
        });
    </script>


    <script>
        // Check for flash messages and show alerts
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif
        });

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.padding = '15px 20px';
            alertDiv.style.borderRadius = '5px';
            alertDiv.style.color = 'white';
            alertDiv.style.zIndex = '1000';
            alertDiv.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alertDiv.style.animation = 'slideIn 0.3s ease-out';
            alertDiv.style.display = 'flex';
            alertDiv.style.alignItems = 'center';
            alertDiv.style.justifyContent = 'space-between';
            alertDiv.style.minWidth = '300px';
            alertDiv.style.maxWidth = '400px';

            if (type === 'success') {
                alertDiv.style.backgroundColor = '#4CAF50';
            } else {
                alertDiv.style.backgroundColor = '#F44336';
            }

            const messageSpan = document.createElement('span');
            messageSpan.textContent = message;
            alertDiv.appendChild(messageSpan);

            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.background = 'transparent';
            closeBtn.style.border = 'none';
            closeBtn.style.color = 'white';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.fontSize = '20px';
            closeBtn.style.marginLeft = '15px';
            closeBtn.addEventListener('click', function() {
                alertDiv.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    alertDiv.remove();
                }, 300);
            });
            alertDiv.appendChild(closeBtn);

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    alertDiv.remove();
                }, 300);
            }, 5000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
        document.head.appendChild(style);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addonCheckboxes = document.querySelectorAll('.addon-checkbox');
            const grandTotalEl = document.getElementById('grand-total');

            function formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }

            function recalculateTotal() {
                let baseTotal = parseInt(grandTotalEl.getAttribute('data-base-total')) || 0;
                let addonTotal = 0;

                addonCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        addonTotal += parseInt(cb.dataset.price || 0);
                    }
                });

                const finalTotal = baseTotal + addonTotal;
                grandTotalEl.textContent = formatRupiah(finalTotal);
            }

            addonCheckboxes.forEach(cb => {
                cb.addEventListener('change', recalculateTotal);
            });
        });
    </script>

    <script>
        function nextStep() {
            const step1 = document.querySelector('.step-1');
            const step2 = document.querySelector('.step-2');
            const submitBtn = document.querySelector('button[type="submit"]');
            const nextBtn = document.querySelector('button[onclick="nextStep()"]'); // tombol "Go To Payment"

            step1.style.display = 'none';
            step2.style.display = 'block';

            // Sembunyikan tombol "Go To Payment"
            if (nextBtn) nextBtn.style.display = 'none';

            // Tampilkan tombol submit
            submitBtn.style.display = 'inline-block';
        }
    </script>
</body>

</html>

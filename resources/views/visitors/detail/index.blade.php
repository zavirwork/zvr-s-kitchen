<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .product-detail-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .carousel-inner img {
            aspect-ratio: 1 / 1;
            /* Rasio kotak */
            width: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .product-info {
            padding: 2rem;
        }

        .product-info h2 {
            font-weight: bold;
        }

        .price {
            font-size: 1.5rem;
            color: hsl(32, 100%, 59%);;
            font-weight: 600;
        }

        .description {
            font-size: 1rem;
            color: #555;
            margin-top: 1rem;
        }

        .btn-action {
            margin-top: 2rem;
        }

        .custom-btn {
            background-color: hsl(32, 100%, 59%);;
            /* Ubah warna background */
            color: #fff;
            /* Warna teks */
            border: none;
            /* Menghapus border default */
        }

        .custom-btn:hover {
            background-color: hsl(32, 100%, 59%);;
            /* Warna saat hover (lebih gelap dari warna utama) */
            color: #fff;
        }

        .custom-btn-outline {
            border: 2px solid hsl(32, 100%, 59%);;
            /* Warna border */
            color: hsl(32, 100%, 59%);;
            /* Warna teks */
            background-color: transparent;
            /* Background transparan */
        }

        .custom-btn-outline:hover {
            background-color: hsl(32, 100%, 59%);;
            /* Mengubah background saat hover */
            color: #fff;
            /* Mengubah warna teks saat hover */
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="product-detail-card row mx-auto">

            <!-- Carousel -->
            <div class="col-md-6 p-0">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100"
                                    alt="Gambar {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="col-md-6 product-info">
                <h2>{{ $product->name }}</h2>
                <p class="text-muted mb-2">Kategori: <strong>{{ ucfirst($product->type) }}</strong></p>
                <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <div class="description">
                    <strong>Deskripsi:</strong>
                    <p class="mt-2">{{ $product->description ?? 'Tidak ada deskripsi yang tersedia.' }}</p>
                </div>

                <div class="btn-action">
                    <button onclick="addToCart({{ $product->id }}, 1)" class="btn custom-btn btn-lg w-100 mb-2">
                        + Tambah ke Keranjang
                    </button>
                    <a href="{{ url('/') }}" class="btn custom-btn-outline w-100">← Kembali ke Menu</a>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(productId, quantity = 1) {
            fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // atau tampilkan toast, badge update, dsb
                    updateCartBadge(data.cart_count);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add to cart.');
                });
        }

        function updateCartBadge(count) {
            const badge = document.querySelector('.cart-badge');
            if (badge) badge.textContent = count;
        }
    </script>
</body>

</html>

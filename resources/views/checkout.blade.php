<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Checkout Produk</h2>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        {{-- Informasi Pembeli --}}
        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama</label>
            <input type="text" class="form-control" name="customer_name" required>
        </div>

        <div class="mb-3">
            <label for="customer_whatsapp" class="form-label">Nomor WhatsApp</label>
            <input type="text" class="form-control" name="customer_whatsapp" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Catatan Tambahan (opsional)</label>
            <textarea class="form-control" name="message" rows="3"></textarea>
        </div>

        <hr>
        <h5 class="mb-3">Pilih Produk</h5>

        {{-- Daftar Produk --}}
        @foreach ($products as $index => $product)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title">{{ $product->name }} <small class="text-muted">({{ ucfirst($product->type) }})</small></h6>
                    <p class="mb-1">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mb-2">Stok: {{ $product->stock }}</p>
                    <div class="mb-3">
                        <label for="cart[{{ $index }}][quantity]" class="form-label">Jumlah</label>
                        <input type="number" name="cart[{{ $index }}][quantity]" class="form-control" min="0" max="{{ $product->stock }}" value="0">
                        <input type="hidden" name="cart[{{ $index }}][product_id]" value="{{ $product->id }}">
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success w-100">Kirim Pesanan</button>
    </form>
</div>
</body>
</html>

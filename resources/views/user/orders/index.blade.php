@extends('user.master')
@section('page-title', 'My Orders')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>My Orders</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Order ID</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Date</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Total</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">#{{ $order->id }}</td>
                                            <td class="text-center">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                @php
                                                    $badgeClass = match ($order->status) {
                                                        'pending' => 'bg-secondary',
                                                        'confirmed' => 'bg-primary',
                                                        'shipped' => 'bg-info',
                                                        'completed' => 'bg-success',
                                                        'cancelled' => 'bg-danger',
                                                        default => 'bg-dark',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td class="text-center">Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-dark mt-2" data-bs-toggle="modal"
                                                    data-bs-target="#orderModal"
                                                    data-order-url="{{ route('user.orders.show', $order->id) }}">
                                                    Lihat Detail
                                                </button>

                                                @if ($order->status === 'completed' && !$order->rating)
                                                    <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal"
                                                        data-bs-target="#ratingModal"
                                                        onclick="setRatingOrderId({{ $order->id }})">
                                                        Beri Rating
                                                    </button>
                                                @elseif($order->rating)
                                                    <span class="badge bg-success mt-2">Sudah Diulas</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Order Detail -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="orderModalContent">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rating -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="ratingForm" action="">
                    @csrf
                    <input type="hidden" name="order_id" id="rating_order_id">
                    <input type="hidden" name="rating" id="starValue" required>

                    <div class="modal-header">
                        <h5 class="modal-title" id="ratingModalLabel">Beri Rating Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold">Berikan Rating</label><br>
                            <div class="star-rating" id="starRating">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small>Tidak Puas</small>
                                <small>Sangat Puas</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="review" class="form-label fw-bold">Ulasan (opsional)</label>
                            <textarea class="form-control" id="review" name="review" rows="3"
                                placeholder="Bagaimana pengalaman Anda dengan pesanan ini?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        function setRatingOrderId(orderId) {
            const form = document.getElementById('ratingForm');
            const orderIdInput = document.getElementById('rating_order_id');

            // Set action form
            form.setAttribute('action', "{{ route('user.orders.rate', ['order' => '_ORDER_ID_']) }}".replace('_ORDER_ID_',
                orderId));
            orderIdInput.value = orderId;

            const stars = document.querySelectorAll('.star');
            const starValue = document.getElementById('starValue');
            let currentRating = 0;

            stars.forEach(star => {
                star.classList.remove('selected', 'hovered');

                // Saat klik
                star.addEventListener('click', function() {
                    currentRating = parseInt(this.dataset.value);
                    starValue.value = currentRating;
                    updateStars();
                });

                // Saat hover
                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(value);
                });

                // Saat keluar dari bintang
                star.addEventListener('mouseout', function() {
                    resetStars();
                });
            });

            function updateStars() {
                stars.forEach(s => {
                    const value = parseInt(s.dataset.value);
                    s.classList.toggle('selected', value <= currentRating);
                });
            }

            function highlightStars(rating) {
                stars.forEach(s => {
                    const value = parseInt(s.dataset.value);
                    s.classList.toggle('hovered', value <= rating);
                });
            }

            function resetStars() {
                stars.forEach(s => {
                    s.classList.remove('hovered');
                });
            }
        }
    </script>

    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top',
            }
        });

        @if (session('success'))
            notyf.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            notyf.error("{{ session('error') }}");
        @endif
    </script>

    <script>
        // Initialize modal
        const orderModal = document.getElementById('orderModal');

        // Order modal show event
        orderModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-order-url');
            const content = document.getElementById('orderModalContent');

            // Show loading spinner
            content.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(order => {
                    // Build items table
                    let itemsHtml = '';
                    if (order.items && order.items.length > 0) {
                        order.items.forEach(item => {
                            const name = item.product ? item.product.name : 'Produk tidak ditemukan';
                            const qty = item.quantity;
                            const price = item.price_at_time;
                            const subtotal = qty * price;

                            itemsHtml += `
                            <tr>
                                <td>${name}</td>
                                <td class="text-end">${qty}</td>
                                <td class="text-end">Rp${price.toLocaleString('id-ID')}</td>
                                <td class="text-end">Rp${subtotal.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                        });
                    } else {
                        itemsHtml = '<tr><td colspan="4" class="text-center">Tidak ada item.</td></tr>';
                    }

                    // Build complete modal content (without rating)
                    content.innerHTML = `
                    <div class="order-detail-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Order #${order.id}</h5>
                            <span class="badge ${getStatusBadgeClass(order.status)}">
                                ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Pesanan</label>
                            <p>${new Date(order.created_at).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric',
                            })}</p>
                        </div>

                        ${order.message ? `
                                <div class="mb-3">
                                    <label class="form-label text-muted">Catatan</label>
                                    <p>${order.message}</p>
                                </div>
                            ` : ''}

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end">Rp${order.total_price.toLocaleString('id-ID')}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                `;
                })
                .catch(error => {
                    content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Gagal memuat detail pesanan: ${error.message}
                    </div>
                `;
                });
        });

        // Helper function to get status badge class
        function getStatusBadgeClass(status) {
            const classes = {
                'pending': 'bg-secondary',
                'confirmed': 'bg-primary',
                'shipped': 'bg-info',
                'completed': 'bg-success',
                'cancelled': 'bg-danger'
            };
            return classes[status] || 'bg-dark';
        }
    </script>


    <style>
        .star-rating {
            font-size: 2.5rem;
            color: #ddd;
            display: flex;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .star-rating .star {
            transition: color 0.2s;
        }

        .star-rating .star.selected,
        .star-rating .star.hovered {
            color: #ffc107;
        }

        .star-rating .star:not(.selected):not(.hovered) {
            color: #ccc;
        }
    </style>
@endsection

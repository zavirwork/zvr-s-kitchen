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
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">No</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">Order ID</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">Date</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">Status</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">Total</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">Action</th>
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
                                            $badgeClass = match($order->status) {
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
                                    <td class="text-center">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-dark mt-2" 
                                            data-bs-toggle="modal"
                                            data-bs-target="#orderModal"
                                            data-order-url="{{ route('user.orders.show', $order->id) }}">
                                            Lihat Detail
                                        </button>
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
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    const orderModal = document.getElementById('orderModal');

    orderModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-order-url');
        const content = document.getElementById('orderModalContent');
        content.innerHTML = '<p>Loading...</p>';

        fetch(url)
            .then(response => response.json())
            .then(order => {
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
                                <td>${qty}</td>
                                <td>Rp${price}</td>
                                <td>Rp${subtotal}</td>
                            </tr>
                        `;
                    });
                } else {
                    itemsHtml = '<tr><td colspan="4">Tidak ada item.</td></tr>';
                }

                const message = order.message ?? '-';
                const status = order.status;

                content.innerHTML = `
                    <p><strong>Order ID:</strong> #${order.id}</p>
                    <p><strong>Status:</strong> ${status}</p>
                    <p><strong>Catatan:</strong> ${message}</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end"><strong>Total:</strong> Rp${order.total_price}</div>
                `;
            })
            .catch(error => {
                content.innerHTML = `<p class="text-danger">Terjadi kesalahan: ${error.message}</p>`;
            });
    });
</script>
@endsection

@extends('admin.master')
@section('page-title', 'Orders')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Orders table</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Customer Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Customer Number</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Total Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Maps</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Evidence</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td class="text-center align-middle text-sm">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle text-sm">{{ $order->customer_name }}</td>
                                            <td class="text-center align-middle text-sm"><a
                                                    href="https://wa.me/{{ $order->customer_whatsapp }}"><i
                                                        class="ni ni-send"></i>
                                                    {{ $order->customer_whatsapp }}</a></td>
                                            <td class="text-center align-middle text-sm">Rp.{{ $order->total_price }}</td>
                                            <td class="text-center align-middle text-sm">
                                                @php
                                                    switch ($order->status) {
                                                        case 'pending':
                                                            $badgeClass = 'bg-gradient-secondary';
                                                            break;
                                                        case 'confirmed':
                                                            $badgeClass = 'bg-gradient-primary';
                                                            break;
                                                        case 'shipped':
                                                            $badgeClass = 'bg-gradient-info';
                                                            break;
                                                        case 'completed':
                                                            $badgeClass = 'bg-gradient-success';
                                                            break;
                                                        case 'cancelled':
                                                            $badgeClass = 'bg-gradient-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'bg-gradient-secondary';
                                                            break;
                                                    }
                                                @endphp

                                                <span
                                                    class="badge badge-sm {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>

                                            </td>
                                            <td>
                                                @if ($order->latitude && $order->longitude)
                                                    <center>
                                                        <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                            View Map
                                                        </a>
                                                    </center>
                                                @else
                                                    <span class="text-muted">No Location</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle text-sm">
                                                @if ($order->evidence_transfer)
                                                    <a href="{{ asset('storage/' . $order->evidence_transfer) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-success mt-2">
                                                        View
                                                    </a>
                                                @else
                                                    <span class="text-muted">No File</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle text-sm">
                                                <div class="dropdown">
                                                    <button class="badge badge-sm bg-gradient-info dropdown-toggle border-0"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#seeOrderModal"
                                                                data-order-url="{{ route('admin.orders.show', $order->id) }}">
                                                                See
                                                            </a>

                                                        </li>
                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#updateModal{{ $order->id }}">
                                                                Update
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal see detail -->
                                        <div class="modal fade" id="seeOrderModal" tabindex="-1"
                                            aria-labelledby="seeOrderModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="seeOrderModalLabel">Detail Order</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" id="orderDetailContent">
                                                        <p>Please wait...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Update -->
                                        <div class="modal fade" id="updateModal{{ $order->id }}" tabindex="-1"
                                            aria-labelledby="updateModalLabel{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.orders.update_status', $order->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="updateModalLabel{{ $order->id }}">Status Order
                                                                Update
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="statusSelect{{ $order->id }}"
                                                                    class="form-label">Status</label>
                                                                <select class="form-select"
                                                                    id="statusSelect{{ $order->id }}" name="status"
                                                                    required>
                                                                    @foreach (['pending', 'confirmed', 'shipped', 'completed', 'cancelled'] as $status)
                                                                        <option value="{{ $status }}"
                                                                            {{ $order->status === $status ? 'selected' : '' }}>
                                                                            {{ ucfirst($status) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No orders found.</td>
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
@endsection
@section('script')
<script>
    const seeOrderModal = document.getElementById('seeOrderModal');

    // Format angka jadi format mata uang Indonesia
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    seeOrderModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-order-url');
        const content = document.getElementById('orderDetailContent');

        content.innerHTML = '<p>Please wait...</p>';

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(order => {
                let itemsHtml = '';

                if (order.items && order.items.length > 0) {
                    order.items.forEach(item => {
                        const name = item.product ? item.product.name : 'Produk tidak ditemukan';
                        const price = parseFloat(item.price_at_time);
                        const qty = item.quantity;
                        const subtotal = price * qty;

                        // Handle Addons
                        let addonsHtml = '';
                        if (item.addons && item.addons.length > 0) {
                            addonsHtml += '<ul class="mb-0 ps-3">';
                            item.addons.forEach(addon => {
                                addonsHtml += `<li>${addon.name} (Rp${formatRupiah(addon.pivot.price_at_time)})</li>`;
                            });
                            addonsHtml += '</ul>';
                        }

                        itemsHtml += `
                            <tr>
                                <td>
                                    ${name}
                                    ${addonsHtml}
                                </td>
                                <td>${qty}</td>
                                <td>Rp${formatRupiah(price)}</td>
                                <td>Rp${formatRupiah(subtotal)}</td>
                            </tr>
                        `;
                    });
                } else {
                    itemsHtml = '<tr><td colspan="4">No items available.</td></tr>';
                }

                let message = order.message ?? '-';
                if (message && message !== '-') {
                    const messageLines = message
                        .split(';')
                        .map(line => line.trim())
                        .filter(line => line.length > 0)
                        .map(line => `- ${line}`);
                    
                    message = messageLines.join('<br>');
                }

                content.innerHTML = `
                    <div class="mb-3">
                        <strong>Order ID:</strong> #${order.id}<br>
                        <strong>Customer Name:</strong> ${order.customer_name}<br>
                        <strong>WhatsApp Number:</strong> ${order.customer_whatsapp}<br>
                        <strong>Status:</strong> ${order.status}<br>
                        <strong>Message:</strong><br> ${message}<br>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end">
                        <strong>Total:</strong> Rp${formatRupiah(order.total_price)}
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = `<p class="text-danger">Something went wrong: ${error.message}</p>`;
            });
    });
</script>
@endsection


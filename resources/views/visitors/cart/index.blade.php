<div id="cart-section">
    @if(count($cart) > 0)
        <ul>
            @foreach ($cart as $item)
            <li class="cart-item" data-id="{{ $item['id'] }}">
                <div class="item-info">
                    <span class="item-name">{{ $item['name'] }}</span>
            
                    <div class="qty-control">
                        <button onclick="updateQuantity({{ $item['id'] }}, -1)">-</button>
                        <input type="number" min="1" value="{{ $item['quantity'] }}" readonly id="qty-{{ $item['id'] }}">
                        <button onclick="updateQuantity({{ $item['id'] }}, 1)">+</button>
                        <span>x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                    </div>
            
                    <div class="item-total">
                        Total: Rp <span id="total-{{ $item['id'] }}">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                </div>
            
                <button class="btn-delete" onclick="removeItem({{ $item['id'] }})">
                    🗑 Hapus
                </button>
            </li>
                      
            @endforeach
        </ul>

        <h3>Total Harga: Rp <span id="cart-total">{{ number_format($total, 0, ',', '.') }}</span></h3>
    @else
        <p>Keranjang Anda kosong.</p>
    @endif
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zvr's Kitchen - More than Delicious, It's Fantastic!</title>

    <!--
    - favicon
  -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/svg+xml">

    <!--
    - custom css link
  -->
    <link rel="stylesheet" href="{{ asset('assets-visitor/css/style.css') }}">

    <!--
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Rubik:wght@400;500;600;700&family=Shadows+Into+Light&display=swap"
        rel="stylesheet">

    <!--
    - preload images
  -->
    <link rel="preload" as="image" href="{{ asset('assets-visitor/images/hero-banner.png') }}"
        media="min-width(768px)">
    <link rel="preload" as="image" href="{{ asset('assets-visitor/images/hero-banner-bg.png') }}"
        media="min-width(768px)">
    <link rel="preload" as="image" href="{{ asset('assets-visitor/images/hero-bg.jpg') }}">

    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            width: 90%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            cursor: pointer;
            font-size: 1.5rem;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fafafa;
            padding: 15px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .item-name {
            font-weight: bold;
            font-size: 16px;
        }

        .qty-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-control input[type="number"] {
            width: 50px;
            text-align: center;
        }

        .item-total {
            font-weight: bold;
            color: #333;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        #cart-section h3 {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: right;
        }

        #cart-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #cart-section li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }

        #cart-section li span {
            font-weight: 600;
            flex: 1 1 100%;
        }

        .qty-control {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }

        .qty-control button {
            background-color: #ff8c00;
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
        }

        .qty-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
            background: #f9f9f9;
        }

        .item-total {
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        #cart-total {
            font-size: 20px;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.2s;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body id="top">

    <!--
    - #HEADER
  -->

    <header class="header" data-header>
        <div class="container">
            @if (session('success'))
                <script>
                    window.onload = function() {
                        alert("{{ session('success') }}"); // Menampilkan pesan sukses
                    };
                </script>
            @endif

            <h1>
                <a href="#" class="logo">Zvr's<span class="span">Kitchen</span></a>
            </h1>

            <nav class="navbar" data-navbar>
                <ul class="navbar-list">

                    <li class="nav-item">
                        <a href="#home" class="navbar-link" data-nav-link>Home</a>
                    </li>

                    <li class="nav-item">
                        <a href="#food-menu" class="navbar-link" data-nav-link>Shop</a>
                    </li>

                    <li class="nav-item">
                        <a href="#testimoni" class="navbar-link" data-nav-link>Testimonials</a>
                    </li>

                    <li class="nav-item">
                        @guest
                            <a href="{{ route('login') }}" class="navbar-link"><u>Login</u></a>
                        @endguest

                        @auth
                            @php
                                $dashboardRoute = Auth::user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard';
                            @endphp
                            <a href="{{ route($dashboardRoute) }}" class="navbar-link">
                                <u>{{ Auth::user()->name }}</u>
                            </a>
                        @endauth
                    </li>
                </ul>
            </nav>

            <div class="header-btn-group">
                <a href="javascript:void(0);" onclick="openCartModal()" aria-label="Lihat Keranjang"
                    class="btn btn-hover" data-nav-link style="display: flex; align-items: center;">
                    <ion-icon name="basket-outline" size="large"></ion-icon>
                    <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                </a>

                <button class="nav-toggle-btn" aria-label="Toggle Menu" data-menu-toggle-btn>
                    <span class="line top"></span>
                    <span class="line middle"></span>
                    <span class="line bottom"></span>
                </button>
            </div>

        </div>
    </header>

    <main>
        <article>

            <!--
        - #HERO
      -->

            <section class="hero" id="home"
                style="background-image: url('{{ asset('assets-visitor/images/hero-bg.jpg') }}')">
                <div class="container">

                    <div class="hero-content">

                        <p class="hero-subtitle">Eat Feel and Be Happy!</p>

                        <h2 class="h1 hero-title">Super Delicious Food in Town!</h2>

                        <p class="hero-text">Satu Gigitan, Seribu Kesan.</p>

                        {{-- <button class="btn">Book A Table</button> --}}

                    </div>

                    <figure class="hero-banner">
                        <img src="{{ asset('assets-visitor/images/hero-banner-bg.png') }}" width="820"
                            height="716" alt="" aria-hidden="true" class="w-100 hero-img-bg">

                        <img src="{{ asset('assets-visitor/images/hero-banner.png') }}" width="700" height="637"
                            loading="lazy" alt="Burger" class="w-100 hero-img">
                    </figure>

                </div>
            </section>

            <!--
        - #FOOD MENU
      -->

            <section class="section food-menu" id="food-menu">
                <div class="container">

                    {{-- <p class="section-subtitle">Popular Dishes</p> --}}

                    <h2 class="h2 section-title">
                        Our Delicious <span class="span">Foods Menu</span>
                    </h2>

                    {{-- <p class="section-text">
                        Food is any substance consumed to provide nutritional support for an organism.
                    </p> --}}

                    <ul class="fiter-list">
                        <li><a href="{{ url('/') }}"
                                class="filter-btn {{ empty($type) ? 'active' : '' }}">All</a></li>
                        <li><a href="{{ url('/?type=food') }}"
                                class="filter-btn {{ $type === 'food' ? 'active' : '' }}">Food</a></li>
                        <li><a href="{{ url('/?type=drink') }}"
                                class="filter-btn {{ $type === 'drink' ? 'active' : '' }}">Drink</a></li>
                    </ul>

                    <ul class="food-menu-list">
                        @foreach ($products as $item)
                            <li>
                                <div class="food-menu-card">
                                    <a href="{{ route('products.show', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        <div class="card-banner">
                                            <img src="{{ asset('storage/' . $item->images[0]->path) }}"
                                                width="300" height="300" loading="lazy"
                                                alt="{{ $item->name }}" class="w-100">
                                        </div>
                                    </a>

                                    <div class="wrapper">
                                        <p class="category">{{ $item->type }}</p>
                                    </div>

                                    <h3 class="h3 card-title">{{ $item->name }}</h3>

                                    <div class="price-wrapper">
                                        <p class="price-text">Price:</p>
                                        <data class="price">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</data>
                                    </div>
                                    <br>
                                    <button class="btn btn-primary w-100" type="button"
                                        onclick="event.preventDefault(); addToCart({{ $item->id }}, 1)">Add to
                                        Cart</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <!--
        - #CTA
      -->

            <section class="section section-divider white cta"
                style="background-image: url('{{ asset('assets-visitor/images/hero-bg.jpg') }}')">
                <div class="container">

                    <div class="cta-content">

                        <h2 class="h2 section-title">
                            The Foodie Have Excellent Of
                            <span class="span">Quality Burgers!</span>
                        </h2>

                        {{-- <p class="section-text">
                            The restaurants in Hangzhou also catered to many northern Chinese who had fled south from
                            Kaifeng during
                            the Jurchen
                            invasion of the 1120s, while it is also known that many restaurants were run by families.
                        </p> --}}

                        {{-- <button class="btn btn-hover">Order Now</button> --}}
                    </div>

                    <figure class="cta-banner">
                        <img src="{{ asset('assets-visitor/images/cta-banner.png') }}" width="700" height="637"
                            loading="lazy" alt="Burger" class="w-100 cta-img">

                        {{-- <img src="{{ asset('assets-visitor/images/sale-shape.png') }}" width="216" height="226"
                            loading="lazy" alt="get up to 50% off now" class="abs-img scale-up-anim"> --}}
                    </figure>

                </div>
            </section>

            <!--
        - #TESTIMONIALS
      -->

            <section class="section section-divider white testi" id="testimoni">
                <div class="container">

                    <p class="section-subtitle">Testimonials</p>

                    <h2 class="h2 section-title">
                        Our Customers <span class="span">Reviews</span>
                    </h2>

                    {{-- <p class="section-text">
                        Food is any substance consumed to provide nutritional
                        support for an organism.
                    </p> --}}

                    <ul class="testi-list has-scrollbar">
                        @foreach ($testimoni as $item)
                            <li class="testi-item">
                                <div class="testi-card">

                                    {{-- Profile --}}
                                    <div class="profile-wrapper">
                                        <figure class="avatar">
                                            <img src="{{ asset('assets-visitor/images/user.png') }}" width="80"
                                                height="80" loading="lazy" alt="{{ $item->user->name }}">
                                        </figure>

                                        <div>
                                            <h3 class="h4 testi-name">{{ $item->user->name }}</h3>
                                        </div>
                                    </div>

                                    {{-- Review --}}
                                    <blockquote class="testi-text">
                                        "{{ $item->review }}"
                                    </blockquote>

                                    {{-- Rating Stars --}}
                                    <div class="rating-wrapper">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <ion-icon name="star"
                                                style="color: {{ $i <= $item->rating ? '#ffc107' : '#e4e5e9' }}"></ion-icon>
                                        @endfor
                                    </div>

                                </div>
                            </li>
                        @endforeach

                    </ul>

                </div>
            </section>
    </main>

    <!-- Modal Cart -->
    <div id="cartModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeCartModal()">&times;</span>
            <div id="cartContent">
                <p>Loading cart...</p>
            </div>
        </div>
    </div>

    <!--
    - #FOOTER
  -->

    <footer class="footer">

        <div class="footer-top"
            style="background-image: url('{{ asset('assets-visitor/images/footer-illustration.png') }}')">
            <div class="container">

                <div class="footer-brand">

                    <a href="" class="logo">Zvr's<span class="span">Kitchen</span></a>

                    <p class="footer-text">
                        Rasakan perbedaan dalam setiap suapan — karena kualitas tak bisa ditiru.
                    </p>

                    <ul class="social-list">

                        <li>
                            <a href="https://wa.me/6281934131038" class="social-link">
                                <ion-icon name="logo-whatsapp"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="https://www.instagram.com/zvrs_kitchen?igsh=a2FoenAyMHh1eHZ5"
                                class="social-link">
                                <ion-icon name="logo-instagram"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-pinterest"></ion-icon>
                            </a>
                        </li>

                    </ul>

                </div>

                <ul class="footer-list">

                    <li>
                        <p class="footer-list-title">Contact Info</p>
                    </li>

                    <li>
                        <p class="footer-list-item"> (+62) 819-3413-1038</p>
                    </li>

                    <li>
                        <p class="footer-list-item">kitchenzvrs@gmail.com</p>
                    </li>

                    <li>
                        <address class="footer-list-item">JL. Musi No. 48, RT04/RW01, Kel. Penganjuran, Kec.
                            Banyuwangi, Kab. Banyuwangi</address>
                    </li>

                </ul>

                <ul class="footer-list">

                    <li>
                        <p class="footer-list-title">Opening Hours</p>
                    </li>

                    <li>
                        <p class="footer-list-item">EVERYDAY!</p>
                    </li>

                    <li>
                        <p class="footer-list-item">at 9.00 AM - 9.00 PM</p>
                    </li>

                    {{-- <li>
                        <p class="footer-list-item">Saturday: 10:00-16:00</p>
                    </li> --}}

                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright-text">
                    &copy; 2022 <a href="#" class="copyright-link">codewithsadee</a> All Rights Reserved.
                </p>
            </div>
        </div>

    </footer>

    <!--
    - custom js link
  -->
    <script src="{{ asset('assets-visitor/js/script.js') }}" defer></script>

    <!--
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    {{-- Opern cart modal --}}
    <script>
        const checkoutUrl = "{{ route('checkout.index') }}";

        function openCartModal() {
            const modal = document.getElementById('cartModal');
            const cartContent = document.getElementById('cartContent');
            modal.style.display = 'flex';

            fetch('{{ route('cart.index') }}')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cartSection = doc.querySelector('#cart-section');

                    if (cartSection) {
                        cartContent.innerHTML = cartSection.innerHTML;

                        // Cek apakah ada item
                        const cartItems = cartSection.querySelectorAll('.cart-item');
                        if (cartItems.length > 0) {
                            const checkoutWrapper = document.createElement('div');
                            checkoutWrapper.style.marginTop = '20px';
                            checkoutWrapper.innerHTML = `
                        <a href="${checkoutUrl}">
                            <button class="btn btn-primary w-30" id="checkoutButton">
                                Checkout
                            </button>
                        </a>
                    `;
                            cartContent.appendChild(checkoutWrapper);
                        }
                    } else {
                        cartContent.innerHTML = '<p>Empty cart or failed to load.</p>';
                    }
                })
                .catch(error => {
                    cartContent.innerHTML = '<p>Failde to load cart</p>';
                    console.error(error);
                });
        }

        function closeCartModal() {
            document.getElementById('cartModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('cartModal');
            if (event.target == modal) {
                closeCartModal();
            }
        }
    </script>

    {{-- Add products to cart --}}
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

    {{-- Update quantity order --}}
    <script>
        function updateQuantity(productId, change) {
            const qtyInput = document.getElementById(`qty-${productId}`);
            let newQty = parseInt(qtyInput.value) + change;
            if (newQty < 1) return;

            fetch(`/cart/update-quantity/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: newQty
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        qtyInput.value = data.quantity;
                        document.getElementById(`total-${productId}`).innerText = data.item_total;
                        document.getElementById('cart-total').innerText = data.cart_total;
                    } else {
                        alert('Failed to update');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong');
                });
        }
    </script>
    <script>
        function removeItem(productId) {
            fetch(`/cart/remove/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    // Hapus elemen dari DOM
                    const item = document.querySelector(`li[data-id="${productId}"]`);
                    if (item) item.remove();

                    // Update total
                    document.getElementById('cart-total').textContent = data.total_formatted;

                    // Update badge jika perlu
                    updateCartBadge(data.cart_count);
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to delete.');
                });
        }
    </script>

    <script>
        function updateNote(productId, note) {
            fetch(`/cart/update-note/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        note: note
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Gagal menyimpan catatan');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan catatan');
                });
        }

        // Event delegation: jika cart sudah di-render dinamis, kita tangkap perubahan input dengan cara ini
        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name^="notes["]')) {
                const productId = e.target.name.match(/\[(\d+)\]/)[1];
                updateNote(productId, e.target.value);
            }
        });
    </script>


    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/68383470000747190e3ff51b/1isdopm30';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

</body>

</html>

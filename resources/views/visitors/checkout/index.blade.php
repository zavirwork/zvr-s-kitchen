<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zvr's Kitchen - Kebab enak dan bergiji!</title>

    <!--
    - favicon
  -->
    <link rel="shortcut icon" href="{{ asset('asset-visitor/favicon.svg') }}" type="image/svg+xml">

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

            <h1>
                <a href="#" class="logo">Zavr's<span class="span">Kitchen</span></a>
            </h1>

        </div>
    </header>

    <main>
        <article>
            <!--
        - #FOOD MENU
      -->

            <section class="section food-menu" id="food-menu">
                <div class="container">

                    <p class="section-subtitle">Popular Dishes</p>

                    <h2 class="h2 section-title">
                        Our Delicious <span class="span">Foods</span>
                    </h2>

                    <p class="section-text">
                        Food is any substance consumed to provide nutritional support for an organism.
                    </p>

                    <ul class="fiter-list">

                        <li>
                            <button class="filter-btn  active">All</button>
                        </li>

                        <li>
                            <button class="filter-btn">Food</button>
                        </li>

                        <li>
                            <button class="filter-btn">Drink</button>
                        </li>

                    </ul>

                    <ul class="food-menu-list">
                        @foreach ($products as $item)
                            <li>
                                <div class="food-menu-card">

                                    <div class="card-banner">
                                        <img src="{{ asset('storage/' . $item->images[0]->path) }}" width="300"
                                            height="300" loading="lazy" alt="Fried Chicken Unlimited"
                                            class="w-100">
                                    </div>

                                    <div class="wrapper">
                                        <p class="category">{{ $item->type }}</p>
                                    </div>

                                    <h3 class="h3 card-title">{{ $item->name }}</h3>

                                    <div class="price-wrapper">

                                        <p class="price-text">Price:</p>

                                        <data class="price">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</data>

                                        {{-- <del class="del" value="69.00">$69.00</del> --}}

                                    </div>
                                    <br>
                                    <button class="btn btn-primary w-100"
                                        onclick="addToCart({{ $item->id }}, 1)">Add to Cart</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

    </main>

    <!--
    - #FOOTER
  -->

    <footer class="footer">

        <div class="footer-top"
            style="background-image: url('{{ asset('assets-visitor/images/footer-illustration.png') }}')">
            <div class="container">

                <div class="footer-brand">

                    <a href="" class="logo">Zavr's<span class="span">Kitchen</span></a>

                    <p class="footer-text">
                        Financial experts support or help you to to find out which way you can raise your funds more.
                    </p>

                    <ul class="social-list">

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-instagram"></ion-icon>
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
                        <p class="footer-list-item">+1 (062) 109-9222</p>
                    </li>

                    <li>
                        <p class="footer-list-item">Info@YourGmail24.com</p>
                    </li>

                    <li>
                        <address class="footer-list-item">153 Williamson Plaza, Maggieberg, MT 09514</address>
                    </li>

                </ul>

                <ul class="footer-list">

                    <li>
                        <p class="footer-list-title">Opening Hours</p>
                    </li>

                    <li>
                        <p class="footer-list-item">Monday-Friday: 08:00-22:00</p>
                    </li>

                    <li>
                        <p class="footer-list-item">Tuesday 4PM: Till Mid Night</p>
                    </li>

                    <li>
                        <p class="footer-list-item">Saturday: 10:00-16:00</p>
                    </li>

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

</body>

</html>

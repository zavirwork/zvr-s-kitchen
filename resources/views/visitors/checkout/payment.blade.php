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
                        <h2>Upload Payment Proof</h2>
                    </div>
                    <div class="checkout-body">
                        <p>Total pembayaran: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </p>

                        <form action="{{ route('checkout.uploadPayment', $order->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="evidence_transfer">Upload Bukti Transfer</label>
                                <input type="file" name="evidence_transfer" class="input-field" required
                                    accept="image/*,application/pdf">
                                <div class="bank-info">
                                    Transfer to: 1247362349 BNI (Mohammad Zavir Zakaria)
                                </div>
                            </div>

                            <button type="submit" class="btn-primary">Submit Payment</button>
                        </form>
                    </div>
                </div>
            </section>
        </article>
    </main>
</body>

</html>

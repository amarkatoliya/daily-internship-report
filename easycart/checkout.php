<?php
// Start session
session_start();

// Include data layer and shared shipping logic
require_once 'data.php';
require_once 'shipping_logic.php';
// print_r($_SESSION);
// Sanitize cart: remove items without valid ID
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
        return isset($item['id']) && $item['id'] > 0;
    });
}

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: cart.php');
    exit;
}

// Get cart items and calculate totals
$cartItems = [];
$subtotal = 0;

foreach ($_SESSION['cart'] as $cartItem) {
    $product = getProductById($cartItem['id']);
    if ($product) {
        $itemTotal = $product['price'] * $cartItem['quantity'];
        $cartItems[] = [
            'product' => $product,
            'quantity' => $cartItem['quantity'],
            'total' => $itemTotal
        ];
        $subtotal += $itemTotal;
    }
}

$totalQuantity = 0;
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['quantity'])) {
        $totalQuantity += (int) $item['quantity'];
    }
}

// Save shipping method to session if posted (for persistence across reloads)
if (isset($_POST['shipping_method'])) {
    $_SESSION['selected_shipping'] = $_POST['shipping_method'];
}

// Calculate shipping using shared logic
// Use session value first, then POST, then default to 'standard'
$selected_shipping = $_SESSION['selected_shipping'] ?? $_POST['shipping_method'] ?? 'standard';
$shipping = calculateShippingCost($selected_shipping, $subtotal);

// Calculate tax using shared logic
$tax = calculateTax($subtotal, $shipping);

$extra_charges = 0; // Will be set based on payment method
$total = $subtotal + $shipping + $tax + $extra_charges;
$cartCount = count($_SESSION['cart']);

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $errors = [];

    // Validate required fields
    $required_fields = ['email', 'first_name', 'last_name', 'address', 'city', 'state', 'zip', 'payment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }

    // Validate payment method specific fields
    $payment_method = $_POST['payment_method'] ?? '';

    if ($payment_method === 'card') {
        $card_fields = ['cardholder_name', 'card_number', 'expiry_date', 'cvv'];
        foreach ($card_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required for card payment';
            }
        }

        // Validate card number (basic check)
        if (!empty($_POST['card_number'])) {
            $card_number = preg_replace('/\s+/', '', $_POST['card_number']);
            if (!preg_match('/^\d{13,19}$/', $card_number)) {
                $errors[] = 'Invalid card number format';
            }
        }

        // Validate expiry date
        if (!empty($_POST['expiry_date'])) {
            if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $_POST['expiry_date'])) {
                $errors[] = 'Invalid expiry date format (MM/YY)';
            } else {
                list($month, $year) = explode('/', $_POST['expiry_date']);
                $current_year = date('y');
                $current_month = date('m');
                if ($year < $current_year || ($year == $current_year && $month < $current_month)) {
                    $errors[] = 'Card has expired';
                }
            }
        }

        // Validate CVV
        if (!empty($_POST['cvv'])) {
            if (!preg_match('/^\d{3,4}$/', $_POST['cvv'])) {
                $errors[] = 'Invalid CVV format';
            }
        }

    } elseif ($payment_method === 'upi') {
        if (empty($_POST['upi_id'])) {
            $errors[] = 'UPI ID is required for UPI payment';
        } elseif (!preg_match('/.+@.+/', $_POST['upi_id'])) {
            $errors[] = 'Invalid UPI ID format';
        }
    }

    // Calculate extra charges based on payment method
    $extra_charges = 0;
    if ($payment_method === 'cod') {
        $extra_charges = 50; // COD charge
    }

    // Recalculate total with tax and extra charges
    $final_total = $subtotal + $shipping + $tax + $extra_charges;

    // If no errors, process the order
    if (empty($errors)) {
        // Generate consistent order ID
        $year = date('Y');
        $existing_orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];
        $order_index = count($existing_orders) + 4; // Start from 004 since static orders go up to 003
        $order_id = sprintf('ORD-%s-%03d', $year, $order_index);

        // Create order data
        $order = [
            'id' => $order_id,
            'date' => date('Y-m-d H:i:s'),
            'customer' => [
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'zip' => $_POST['zip']
            ],
            'payment_method' => $payment_method,
            'shipping_method' => $selected_shipping,
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'extra_charges' => $extra_charges,
            'total' => $final_total,
            'status' => 'CONFIRMED'
        ];

        // Store order in persistent JSON storage
        $ordersFile = 'data/orders.json';
        $allOrders = [];
        if (file_exists($ordersFile)) {
            $jsonContent = file_get_contents($ordersFile);
            $allOrders = json_decode($jsonContent, true) ?: [];
        }
        $allOrders[] = $order;
        file_put_contents($ordersFile, json_encode($allOrders, JSON_PRETTY_PRINT));

        // Also keep in session for immediate feedback
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        $_SESSION['orders'][] = $order;

        // Clear cart and set success message
        $_SESSION['cart'] = [];
        $_SESSION['order_success'] = true;
        $_SESSION['last_order_id'] = $order_id;
        
        // Clear shipping selection after order is placed
        unset($_SESSION['selected_shipping']);

        header('Location: orders.php');
        exit;
    } else {
        // Store errors in session to display
        $_SESSION['checkout_errors'] = $errors;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EasyCart</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/checkout.js" defer></script>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header__content">
                <a href="index.php" class="header__logo">EasyCart</a>
                <nav class="header__nav">
                    <ul class="nav__list">
                        <li><a href="index.php" class="nav__link">Home</a></li>
                        <li><a href="plp.php" class="nav__link">Products</a></li>
                        <li><a href="cart.php" class="nav__link nav__link--cart">Cart
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-badge">
                                        <?php echo $cartCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a></li>
                        <li><a href="orders.php" class="nav__link">Orders</a></li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav__user">
                                <span class="nav__link" style="color: var(--color-primary); font-weight: 600;">
                                    Hi, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>
                                </span>
                            </li>
                            <li><a href="logout.php" class="nav__link"
                                    onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="nav__link">Login</a></li>
                            <li><a href="signup.php" class="nav__link">Signup</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container animate-fade-in-up" id="checkout-container" data-subtotal="<?php echo $subtotal; ?>"
            data-shipping="<?php echo $shipping; ?>" data-total-quantity="<?php echo $totalQuantity; ?>">
            <div class="checkout-steps">
                <div class="checkout-step completed">
                    <div class="checkout-step__number">✓</div>
                    <span>Cart</span>
                </div>
                <div class="checkout-step active">
                    <div class="checkout-step__number">2</div>
                    <span>Shipping</span>
                </div>
                <div class="checkout-step">
                    <div class="checkout-step__number">3</div>
                    <span>Payment</span>
                </div>
            </div>

            <div class="checkout-layout">
                <div class="checkout-main">
                    <?php if (isset($_SESSION['checkout_errors']) && !empty($_SESSION['checkout_errors'])): ?>
                        <div class="checkout-error-alert">
                            <h4>Please fix the following errors:</h4>
                            <ul>
                                <?php foreach ($_SESSION['checkout_errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['checkout_errors']); ?>
                    <?php endif; ?>

                    <form method="POST">
                        <section class="checkout-form-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                Contact Information
                            </h3>
                            <div class="form__group">
                                <label for="email" class="form__label">Email Address</label>
                                <input type="email" id="email" name="email" class="form__input"
                                    placeholder="you@example.com"
                                    value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" required>
                            </div>
                        </section>

                        <section class="checkout-form-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Shipping Address
                            </h3>
                            <div class="checkout-address-grid">
                                <div class="form__group">
                                    <label for="first_name" class="form__label">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form__input"
                                        value="<?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? ''); ?>"
                                        required>
                                </div>
                                <div class="form__group">
                                    <label for="last_name" class="form__label">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form__input"
                                        value="<?php echo htmlspecialchars($_SESSION['user']['last_name'] ?? ''); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="form__group">
                                <label for="address" class="form__label">Address</label>
                                <input type="text" id="address" name="address" class="form__input" required>
                            </div>
                            <div class="checkout-city-state-grid">
                                <div class="form__group">
                                    <label for="city" class="form__label">City</label>
                                    <input type="text" id="city" name="city" class="form__input" required>
                                </div>
                                <div class="form__group">
                                    <label for="state" class="form__label">State</label>
                                    <input type="text" id="state" name="state" class="form__input" required>
                                </div>
                                <div class="form__group">
                                    <label for="zip" class="form__label">ZIP Code</label>
                                    <input type="text" id="zip" name="zip" class="form__input" required>
                                </div>
                            </div>
                        </section>

                        <section class="checkout-form-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                Shipping Method
                            </h3>
                            <div class="payment-methods">
                                <label class="payment-method-card <?php echo ($selected_shipping === 'standard') ? 'active' : ''; ?>">
                                    <input type="radio" name="shipping_method" value="standard" 
                                        <?php echo ($selected_shipping === 'standard') ? 'checked' : ''; ?>
                                        onchange="updateShipping('standard', 'Standard Shipping')">
                                    <div class="shipping-info">
                                        <span class="shipping-name">Standard Shipping</span>
                                        <span class="shipping-time">5-7 Business Days</span>
                                        <span class="shipping-cost">Flat ₹40</span>
                                    </div>
                                </label>
                                <label class="payment-method-card <?php echo ($selected_shipping === 'express') ? 'active' : ''; ?>">
                                    <input type="radio" name="shipping_method" value="express"
                                        <?php echo ($selected_shipping === 'express') ? 'checked' : ''; ?>
                                        onchange="updateShipping('express', 'Express Shipping')">
                                    <div class="shipping-info">
                                        <span class="shipping-name">Express Shipping</span>
                                        <span class="shipping-time">1-2 Business Days</span>
                                        <span class="shipping-cost">₹80 or 10% (whichever is lower)</span>
                                    </div>
                                </label>
                                <label class="payment-method-card <?php echo ($selected_shipping === 'white_glove') ? 'active' : ''; ?>">
                                    <input type="radio" name="shipping_method" value="white_glove"
                                        <?php echo ($selected_shipping === 'white_glove') ? 'checked' : ''; ?>
                                        onchange="updateShipping('white_glove', 'White Glove Delivery')">
                                    <div class="shipping-info">
                                        <span class="shipping-name">White Glove Delivery</span>
                                        <span class="shipping-time">Premium Service</span>
                                        <span class="shipping-cost">₹150 or 5% (whichever is lower)</span>
                                    </div>
                                </label>
                                <label class="payment-method-card <?php echo ($selected_shipping === 'freight') ? 'active' : ''; ?>">
                                    <input type="radio" name="shipping_method" value="freight"
                                        <?php echo ($selected_shipping === 'freight') ? 'checked' : ''; ?>
                                        onchange="updateShipping('freight', 'Freight Shipping')">
                                    <div class="shipping-info">
                                        <span class="shipping-name">Freight Shipping</span>
                                        <span class="shipping-time">Bulk Orders</span>
                                        <span class="shipping-cost">3% or ₹200 minimum</span>
                                    </div>
                                </label>
                            </div>
                        </section>

                        <section class="checkout-form-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Payment Method
                            </h3>
                            <div class="payment-methods">
                                <label class="payment-method-card active">
                                    <input type="radio" name="payment_method" value="card" checked
                                        onchange="togglePaymentForms()">
                                    <span>Credit / Debit Card</span>
                                </label>
                                <label class="payment-method-card">
                                    <input type="radio" name="payment_method" value="upi"
                                        onchange="togglePaymentForms()">
                                    <span>UPI</span>
                                </label>
                                <label class="payment-method-card">
                                    <input type="radio" name="payment_method" value="cod"
                                        onchange="togglePaymentForms()">
                                    <span>Cash on Delivery</span>
                                </label>
                            </div>

                            <!-- Credit Card Form -->
                            <div id="card-form" class="payment-form card-form">
                                <div class="form__group">
                                    <label for="cardholder_name" class="form__label">Cardholder Name</label>
                                    <input type="text" id="cardholder_name" name="cardholder_name" class="form__input"
                                        placeholder="John Doe" required>
                                </div>
                                <div class="form__group">
                                    <label for="card_number" class="form__label">Card Number</label>
                                    <input type="text" id="card_number" name="card_number" class="form__input"
                                        placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                                <div class="checkout-expiry-cvv-grid">
                                    <div class="form__group">
                                        <label for="expiry_date" class="form__label">Expiry Date</label>
                                        <input type="text" id="expiry_date" name="expiry_date" class="form__input"
                                            placeholder="MM/YY" maxlength="5" required>
                                    </div>
                                    <div class="form__group">
                                        <label for="cvv" class="form__label">CVV</label>
                                        <input type="text" id="cvv" name="cvv" class="form__input" placeholder="123"
                                            maxlength="4" required>
                                    </div>
                                </div>
                            </div>

                            <!-- UPI Form -->
                            <div id="upi-form" class="payment-form upi-form" style="display: none;">
                                <div class="form__group">
                                    <label for="upi_id" class="form__label">UPI ID</label>
                                    <input type="text" id="upi_id" name="upi_id" class="form__input"
                                        placeholder="yourname@upi" pattern=".+@.+">
                                </div>
                                <p class="cod-notice-text">
                                    Enter your UPI ID (e.g., yourname@paytm, yourname@ybl)
                                </p>
                            </div>

                            <!-- Cash on Delivery Notice -->
                            <div id="cod-notice" class="payment-form cod-notice" style="display: none;">
                                <div class="cod-notice-header">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <strong>Cash on Delivery</strong>
                                </div>
                                <p class="cod-notice-text">
                                    Pay when your order is delivered to your doorstep. ₹50 additional charges will be
                                    applied for COD payment.
                                </p>
                            </div>
                        </section>

                        <div style="display: none;">
                            <button type="submit" name="place_order" id="place-order-btn"></button>
                        </div>
                    </form>
                </div>

                <aside class="checkout-sidebar checkout-sidebar-sticky">
                    <div class="order-summary">
                        <h3 class="order-summary__title">Order Summary</h3>

                        <div class="order-items-section">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="order-item">
                                    <div class="order-item-image">
                                        <img src="<?php echo htmlspecialchars($item['product']['image_url']); ?>"
                                            alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                        <span class="order-item-quantity">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                    </div>
                                    <div class="order-item-info">
                                        <p><?php echo htmlspecialchars($item['product']['name']); ?></p>
                                        <p><?php echo htmlspecialchars($item['product']['category']); ?></p>
                                    </div>
                                    <div class="order-item-price">
                                        <?php echo formatPrice($item['total']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-summary__row">
                            <span>Subtotal</span>
                            <span>
                                <?php echo formatPrice($subtotal); ?>
                            </span>
                        </div>
                        <div class="order-summary__row">
                            <span>Shipping</span>
                            <span id="summary-shipping">
                                <?php echo formatPrice($shipping); ?>
                            </span>
                        </div>
                        <div class="order-summary__row">
                            <span>Tax (18%)</span>
                            <span id="summary-tax">
                                <?php echo formatPrice($tax); ?>
                            </span>
                        </div>
                        <div class="order-summary__row" id="extra-charges-row" style="display: none;">
                            <span>Extra Charges</span>
                            <span id="extra-charges-amount">
                                <?php echo formatPrice($extra_charges); ?>
                            </span>
                        </div>
                        <div class="order-summary__row order-summary__row--total">
                            <strong>Total</strong>
                            <strong id="final-total">
                                <?php echo formatPrice($total); ?>
                            </strong>
                        </div>

                        <div class="place-order-section">
                            <button type="button" class="btn btn--primary btn--full btn--lg place-order-button"
                                onclick="document.getElementById('place-order-btn').click()" id="place-order-button">
                                Place Order -
                                <?php echo formatPrice($total); ?>
                            </button>
                            <div class="secure-checkout-notice">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span class="secure-checkout-text">Secure Checkout</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="footer footer-checkout">
        <div class="container">
            <div class="footer__bottom">
                <div class="footer__payment">
                    <span>Secure Checkout</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <p>&copy; 2024 EasyCart. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
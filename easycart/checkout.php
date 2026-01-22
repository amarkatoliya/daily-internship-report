<?php
// Start session
session_start();

// Include data layer
require_once 'data.php';

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

$shipping = 500;
$total = $subtotal + $shipping;
$cartCount = count($_SESSION['cart']);

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Here you would process the order
    // For now, we'll just clear the cart and redirect to a success message
    $_SESSION['cart'] = [];
    $_SESSION['order_success'] = true;
    header('Location: orders.php');
    exit;
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
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header__content">
                <a href="index.php" class="header__logo">EasyCart</a>
                <nav class="header__nav">
                    <ul class="nav__list">
                        <li><a href="index.php" class="nav__link">Home</a></li>
                        <li><a href="products.php" class="nav__link">Products</a></li>
                        <li><a href="cart.php" class="nav__link" style="position: relative;">Cart
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-badge">
                                        <?php echo $cartCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a></li>
                        <li><a href="orders.php" class="nav__link">Orders</a></li>
                        <li><a href="login.php" class="nav__link">Login</a></li>
                        <li><a href="signup.php" class="nav__link">Signup</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container animate-fade-in-up">
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
                                <label class="form__label">Email Address</label>
                                <input type="email" name="email" class="form__input" placeholder="you@example.com"
                                    required>
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
                            <div
                                style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                                <div class="form__group">
                                    <label class="form__label">First Name</label>
                                    <input type="text" name="first_name" class="form__input" required>
                                </div>
                                <div class="form__group">
                                    <label class="form__label">Last Name</label>
                                    <input type="text" name="last_name" class="form__input" required>
                                </div>
                            </div>
                            <div class="form__group">
                                <label class="form__label">Address</label>
                                <input type="text" name="address" class="form__input" required>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--space-4);">
                                <div class="form__group">
                                    <label class="form__label">City</label>
                                    <input type="text" name="city" class="form__input" required>
                                </div>
                                <div class="form__group">
                                    <label class="form__label">State</label>
                                    <input type="text" name="state" class="form__input" required>
                                </div>
                                <div class="form__group">
                                    <label class="form__label">ZIP Code</label>
                                    <input type="text" name="zip" class="form__input" required>
                                </div>
                            </div>
                        </section>

                        <section class="checkout-form-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Payment
                            </h3>
                            <div class="payment-methods">
                                <label class="payment-method-card active">
                                    <input type="radio" name="payment" value="card" checked>
                                    <span>Credit Card</span>
                                </label>
                                <label class="payment-method-card">
                                    <input type="radio" name="payment" value="upi">
                                    <span>UPI / Cash on Delivery</span>
                                </label>
                            </div>
                        </section>

                        <div style="display: none;">
                            <button type="submit" name="place_order" id="place-order-btn"></button>
                        </div>
                    </form>
                </div>

                <aside class="checkout-sidebar" style="position: sticky; top: 100px;">
                    <div class="order-summary">
                        <h3 class="order-summary__title">Order Summary</h3>

                        <div
                            style="margin-bottom: var(--space-6); border-bottom: 1px solid var(--border-color-light); padding-bottom: var(--space-4);">
                            <?php foreach ($cartItems as $item): ?>
                                <div style="display: flex; gap: var(--space-3); margin-bottom: var(--space-4);">
                                    <div style="position: relative;">
                                        <img src="<?php echo htmlspecialchars($item['product']['image_url']); ?>"
                                            style="width: 64px; height: 64px; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--border-color);"
                                            alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                        <span
                                            style="position: absolute; top: -8px; right: -8px; background: var(--color-gray-500); color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p style="font-weight: 500; font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($item['product']['name']); ?>
                                        </p>
                                        <p style="color: var(--text-secondary); font-size: 0.85rem;">
                                            <?php echo htmlspecialchars($item['product']['category']); ?>
                                        </p>
                                    </div>
                                    <div style="margin-left: auto; font-weight: 500;">
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
                            <span>
                                <?php echo formatPrice($shipping); ?>
                            </span>
                        </div>
                        <div class="order-summary__row order-summary__row--total">
                            <strong>Total</strong>
                            <strong>
                                <?php echo formatPrice($total); ?>
                            </strong>
                        </div>

                        <div style="margin-top: var(--space-6);">
                            <button type="button" class="btn btn--primary btn--full btn--lg"
                                onclick="document.getElementById('place-order-btn').click()">
                                Place Order -
                                <?php echo formatPrice($total); ?>
                            </button>
                            <div
                                style="display: flex; justify-content: center; gap: var(--space-4); margin-top: var(--space-4); opacity: 0.6;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span style="font-size: 0.75rem;">Secure Checkout</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="footer"
        style="padding: var(--space-8) 0; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="container">
            <div class="footer__bottom"
                style="border: none; padding-top: 0; justify-content: center; flex-direction: column;">
                <div class="footer__payment" style="opacity: 0.8; margin-bottom: var(--space-4);">
                    <span style="font-size: 0.875rem; color: #9ca3af; margin-right: var(--space-2);">Secure
                        Checkout</span>
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
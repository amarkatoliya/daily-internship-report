<?php
// Start session
session_start();

// Include data layer
require_once 'data.php';

// Check for order success message
$orderSuccess = isset($_SESSION['order_success']) && $_SESSION['order_success'];
if ($orderSuccess) {
    unset($_SESSION['order_success']);
}

// Static orders array (simulating past orders)
$staticOrders = [
    [
        'id' => 'ORD-2026-001',
        'date' => '2026-01-15',
        'items' => 3,
        'total' => 33996,
        'status' => 'Delivered',
        'tracking' => 'TRACK1234567890'
    ],
    [
        'id' => 'ORD-2026-002',
        'date' => '2026-01-18',
        'items' => 2,
        'total' => 23498,
        'status' => 'In Transit',
        'tracking' => 'TRACK0987654321'
    ],
    [
        'id' => 'ORD-2026-003',
        'date' => '2026-01-20',
        'items' => 1,
        'total' => 90499,
        'status' => 'Processing',
        'tracking' => 'TRACK1122334455'
    ]
];

// Merge session orders with static orders
$orders = $staticOrders;
if (isset($_SESSION['orders']) && is_array($_SESSION['orders'])) {
    // Convert session orders to the format expected by the display
    foreach ($_SESSION['orders'] as $sessionOrder) {
        $orders[] = [
            'id' => $sessionOrder['id'],
            'date' => date('Y-m-d', strtotime($sessionOrder['date'])),
            'items' => count($sessionOrder['items']),
            'total' => $sessionOrder['total'],
            'status' => ucfirst($sessionOrder['status']),
            'tracking' => 'PENDING' // No tracking yet for new orders
        ];
    }
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - EasyCart</title>
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
                        <li><a href="orders.php" class="nav__link nav__link--active">Orders</a></li>
                        <li><a href="login.php" class="nav__link">Login</a></li>
                        <li><a href="signup.php" class="nav__link">Signup</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <?php if ($orderSuccess): ?>
                <section class="section">
                    <div
                        style="background: var(--color-success-light); border: 1px solid var(--color-success); padding: var(--space-6); border-radius: var(--radius-lg); text-align: center;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)"
                            stroke-width="2" style="margin: 0 auto var(--space-4);">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <h3
                            style="font-size: var(--font-size-2xl); color: var(--color-success); margin-bottom: var(--space-2);">
                            Order Placed Successfully!</h3>
                        <p style="color: var(--text-secondary);">Thank you for your order. We'll send you a confirmation
                            email shortly.</p>
                    </div>
                </section>
            <?php endif; ?>

            <section class="section">
                <h2 class="section__title">My Orders</h2>
                <p class="text-secondary">View and track your orders</p>
            </section>

            <section class="section">
                <div class="table">
                    <div class="table__header">
                        <div class="table__row"
                            style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; gap: var(--space-4);">
                            <div class="table__cell table__cell--header">Order ID</div>
                            <div class="table__cell table__cell--header">Date</div>
                            <div class="table__cell table__cell--header">Items</div>
                            <div class="table__cell table__cell--header">Total</div>
                            <div class="table__cell table__cell--header">Status</div>
                            <div class="table__cell table__cell--header">Tracking</div>
                        </div>
                    </div>
                    <div class="table__body">
                        <?php foreach ($orders as $order): ?>
                            <div class="table__row"
                                style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; gap: var(--space-4);">
                                <div class="table__cell"><strong>
                                        <?php echo htmlspecialchars($order['id']); ?>
                                    </strong></div>
                                <div class="table__cell">
                                    <?php echo date('M d, Y', strtotime($order['date'])); ?>
                                </div>
                                <div class="table__cell">
                                    <?php echo $order['items']; ?> items
                                </div>
                                <div class="table__cell table__cell--price">
                                    <?php echo formatPrice($order['total']); ?>
                                </div>
                                <div class="table__cell">
                                    <?php
                                    $statusClass = '';
                                    switch ($order['status']) {
                                        case 'Delivered':
                                            $statusClass = 'table__cell--status--delivered';
                                            break;
                                        case 'In Transit':
                                            $statusClass = 'table__cell--status--in-transit';
                                            break;
                                        case 'Processing':
                                            $statusClass = 'table__cell--status--processing';
                                            break;
                                    }
                                    ?>
                                    <span class="table__cell--status <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                                <div class="table__cell">
                                    <a href="#"
                                        style="color: var(--color-primary); text-decoration: none; font-weight: 500;">
                                        <?php echo htmlspecialchars($order['tracking']); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-top: var(--space-8); text-align: center;">
                    <a href="products.php" class="btn btn--primary btn--lg">Continue Shopping</a>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3>EasyCart</h3>
                    <p>Your premier online shopping destination. Discover quality products at unbeatable prices.</p>
                    <p>123 Commerce Blvd, Tech City, TC 90210</p>
                </div>
                <div class="footer__section">
                    <h3>Shop</h3>
                    <ul class="footer__links">
                        <li><a href="products.php" class="footer__link">All Products</a></li>
                        <li><a href="products.php?category=Electronics" class="footer__link">Electronics</a></li>
                        <li><a href="products.php?category=Fashion" class="footer__link">Fashion</a></li>
                        <li><a href="products.php" class="footer__link">New Arrivals</a></li>
                    </ul>
                </div>
                <div class="footer__section">
                    <h3>Support</h3>
                    <ul class="footer__links">
                        <li><a href="#" class="footer__link">Help Center</a></li>
                        <li><a href="#" class="footer__link">Order Status</a></li>
                        <li><a href="#" class="footer__link">Shipping Info</a></li>
                        <li><a href="#" class="footer__link">Returns</a></li>
                    </ul>
                </div>
                <div class="footer__section">
                    <h3>Stay Connected</h3>
                    <p>Subscribe to our newsletter for exclusive deals.</p>
                    <form class="footer__newsletter-form">
                        <input type="email" placeholder="Enter your email" class="footer__input">
                        <button type="button" class="btn btn--primary btn--sm">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2024 EasyCart. All rights reserved.</p>
                <div class="footer__social">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path
                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                </div>
                <div class="footer__payment">
                    <svg width="32" height="20" viewBox="0 0 32 20" fill="none">
                        <rect width="32" height="20" rx="3" fill="white" fill-opacity="0.1" />
                        <path d="M13 10L10 16H8L11 10H8L11 4H14L11 10ZM25 4H27V16H25V4ZM19 16H16L19 4H22L19 16Z"
                            fill="white" />
                    </svg>
                    <svg width="32" height="20" viewBox="0 0 32 20" fill="none">
                        <rect width="32" height="20" rx="3" fill="white" fill-opacity="0.1" />
                        <circle cx="11" cy="10" r="6" fill="#EB001B" fill-opacity="0.5" />
                        <circle cx="21" cy="10" r="6" fill="#F79E1B" fill-opacity="0.5" />
                    </svg>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
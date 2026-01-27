<?php
// Start session for cart functionality
session_start();

// Include data layer
require_once 'data.php';

$message = '';
$messageType = '';

// Initialize cart if not exists (must happen before any POST action)
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Sanitize cart: remove invalid items (prevents "Undefined array key" warnings)
$_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) {
    return is_array($item) && isset($item['id']) && (int) $item['id'] > 0 && isset($item['quantity']) && (int) $item['quantity'] > 0;
});
$_SESSION['cart'] = array_values($_SESSION['cart']);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $productId = (int) $_POST['product_id'];
        $quantity = (int) ($_POST['quantity'] ?? 0);

        // Support +/- buttons using delta (increment/decrement by 1)
        if (isset($_POST['delta'])) {
            $delta = (int) $_POST['delta'];
            if ($delta !== 0) {
                foreach ($_SESSION['cart'] as $existingItem) {
                    if (isset($existingItem['id']) && (int) $existingItem['id'] === $productId) {
                        $quantity = (int) ($existingItem['quantity'] ?? 0) + $delta;
                        break;
                    }
                }
            }
        }

        // Validate quantity
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or negative
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
                return isset($item['id']) && (int) $item['id'] != $productId;
            });
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $message = 'Item removed from cart due to invalid quantity.';
            $messageType = 'success';
        } elseif ($quantity > 99) {
            $message = 'Maximum quantity allowed is 99.';
            $messageType = 'error';
        } else {
            // Update quantity
            $updated = false;
            foreach ($_SESSION['cart'] as &$item) {
                if (isset($item['id']) && (int) $item['id'] === $productId) {
                    $item['quantity'] = $quantity;
                    $updated = true;
                    break;
                }
            }
            unset($item); // break reference
            if ($updated) {
                $message = 'Quantity updated successfully.';
                $messageType = 'success';
            } else {
                $message = 'Item not found in cart.';
                $messageType = 'error';
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $productId = (int) $_POST['product_id'];
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
            return isset($item['id']) && (int) $item['id'] != $productId;
        });
        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        $message = 'Item removed from cart.';
        $messageType = 'success';
    } elseif (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
        $message = 'Cart cleared successfully.';
        $messageType = 'success';
    }
}

// Get full cart items with product details
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

$cartCount = count($_SESSION['cart']);
$totalQuantity = 0;
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['quantity'])) {
        $totalQuantity += (int) $item['quantity'];
    }
}
$shipping = 500 * $totalQuantity;
$total = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - EasyCart</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/cart.js" defer></script>
</head>

<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <div class="header__content">
                <a href="index.php" class="header__logo">EasyCart</a>
                <nav class="header__nav">
                    <ul class="nav__list">
                        <li><a href="index.php" class="nav__link">Home</a></li>
                        <li><a href="plp.php" class="nav__link">Products</a></li>
                        <li><a href="cart.php" class="nav__link nav__link--active" style="position: relative;">Cart
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

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <section class="section">
                <h2 class="section__title">Shopping Cart</h2>
                <?php if (!empty($message)): ?>
                    <div class="alert alert--<?php echo $messageType; ?>" style="margin-top: var(--space-4);">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
            </section>

            <?php if (count($cartItems) > 0): ?>
                <!-- Cart Items -->
                <section class="section">
                    <div class="table">
                        <div class="table__header">
                            <div class="table__row"
                                style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px; gap: var(--space-4);">
                                <div class="table__cell table__cell--header">Product</div>
                                <div class="table__cell table__cell--header">Price</div>
                                <div class="table__cell table__cell--header">Quantity</div>
                                <div class="table__cell table__cell--header">Total</div>
                                <div class="table__cell table__cell--header">Action</div>
                            </div>
                        </div>
                        <div class="table__body" id="cart-table-body">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="table__row" id="product-row-<?php echo $item['product']['id']; ?>"
                                    style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 100px; gap: var(--space-4); align-items: center;">
                                    <div class="table__cell">
                                        <div style="display: flex; gap: var(--space-4); align-items: center;">
                                            <img src="<?php echo htmlspecialchars($item['product']['image_url']); ?>"
                                                alt="<?php echo htmlspecialchars($item['product']['name']); ?>"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius);">
                                            <div>
                                                <strong>
                                                    <?php echo htmlspecialchars($item['product']['name']); ?>
                                                </strong>
                                                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 4px;">
                                                    <?php echo htmlspecialchars($item['product']['category']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table__cell">
                                        <?php echo formatPrice($item['product']['price']); ?>
                                    </div>
                                    <div class="table__cell">
                                        <form method="POST"
                                            style="display: inline-flex; gap: var(--space-2); align-items: center;">
                                            <input type="hidden" name="product_id"
                                                value="<?php echo $item['product']['id']; ?>">
                                            <button type="submit" name="update_quantity" value="1" title="Decrease quantity"
                                                style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border-color); background: white; cursor: pointer; <?php echo $item['quantity'] <= 1 ? 'opacity: 0.5; cursor: not-allowed;' : ''; ?>"
                                                onclick="this.form.delta.value = -1;" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                                −
                                            </button>
                                            <input type="hidden" name="delta" value="-1">

                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                                min="1" max="99" required readonly
                                                style="width: 56px; text-align: center; padding: var(--space-2); border: 1px solid var(--border-color); border-radius: var(--radius); background: #f9fafb;">

                                            <button type="submit" name="update_quantity" value="1" title="Increase quantity"
                                                style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border-color); background: white; cursor: pointer;"
                                                onclick="this.form.delta.value = 1;">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                    <div class="table__cell table__cell--price"
                                        id="item-total-<?php echo $item['product']['id']; ?>">
                                        <?php echo formatPrice($item['total']); ?>
                                    </div>
                                    <div class="table__cell">
                                        <form method="POST" class="remove-item-form">
                                            <input type="hidden" name="product_id"
                                                value="<?php echo $item['product']['id']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn--sm remove-item-btn"
                                                style="background: var(--color-danger); color: white;">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="order-summary">
                        <h3 class="order-summary__title">Cart Summary</h3>
                        <div class="order-summary__row">
                            <span>Total Items:</span>
                            <span id="total-quantity-display">
                                <?php echo $totalQuantity; ?>
                            </span>
                        </div>
                        <div class="order-summary__row">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">
                                <?php echo formatPrice($subtotal); ?>
                            </span>
                        </div>
                        <div class="order-summary__row">
                            <span>Shipping:</span>
                            <span id="cart-shipping-display">
                                <?php echo formatPrice($shipping); ?>
                            </span>
                        </div>
                        <div class="order-summary__row order-summary__row--total">
                            <strong>Total:</strong>
                            <strong id="cart-total">
                                <?php echo formatPrice($total); ?>
                            </strong>
                        </div>
                    </div>

                    <!-- Cart Actions -->
                    <div style="display: flex; gap: var(--space-4); margin-top: var(--space-6);">
                        <a href="checkout.php" class="btn btn--primary btn--lg">Proceed to Checkout</a>
                        <a href="plp.php" class="btn btn--outline btn--lg">Continue Shopping</a>
                        <form method="POST" style="margin-left: auto;">
                            <button type="submit" name="clear_cart" class="btn btn--lg"
                                style="background: var(--color-danger); color: white;"
                                onclick="return confirm('Are you sure you want to clear the cart?')">Clear Cart</button>
                        </form>
                    </div>
                </section>
            <?php else: ?>
                <!-- Empty Cart -->
                <section class="section">
                    <div style="text-align: center; padding: var(--space-16) 0;">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                            style="margin: 0 auto var(--space-6); opacity: 0.3;">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <h3 style="font-size: var(--font-size-2xl); margin-bottom: var(--space-4);">Your cart is empty</h3>
                        <p style="color: var(--text-secondary); margin-bottom: var(--space-6);">Add some products to get
                            started</p>
                        <a href="plp.php" class="btn btn--primary btn--lg">Browse Products</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
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
                        <li><a href="plp.php" class="footer__link">All Products</a></li>
                        <li><a href="plp.php?category=Electronics" class="footer__link">Electronics</a></li>
                        <li><a href="plp.php?category=Fashion" class="footer__link">Fashion</a></li>
                        <li><a href="plp.php" class="footer__link">New Arrivals</a></li>
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
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
    <?php include 'includes/header.php'; ?>

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
                                                <?php if (isset($item['product']['shipping_type'])): ?>
                                                    <span
                                                        class="shipping-badge shipping-badge--<?php echo strtolower($item['product']['shipping_type']); ?>"
                                                        style="position: relative; top: 0; left: 0; margin-top: 4px; display: inline-block;">
                                                        <?php echo htmlspecialchars($item['product']['shipping_type']); ?>
                                                    </span>
                                                <?php endif; ?>
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
    <?php include 'includes/footer.php'; ?>
</body>

</html>
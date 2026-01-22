<?php
// Start session for cart functionality
session_start();

// Include data layer
require_once 'data.php';

// Handle Add to Cart redirect logic moved to add_to_cart.php

// Get product ID from URL
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch product data
$product = getProductById($productId);

// If product not found, redirect to products page
if (!$product) {
    header('Location: products.php');
    exit;
}

// Get cart count for badge (filter invalid items)
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count(array_filter($_SESSION['cart'], function ($item) {
        return isset($item['id']) && $item['id'] > 0;
    }));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($product['name']); ?> - EasyCart
    </title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
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

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <section class="section">
                <a href="products.php" class="product-detail__back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M5 12l7-7m-7 7l7 7" />
                    </svg>
                    Back to Products
                </a>
            </section>

            <!-- Product Detail -->
            <section class="section">
                <div class="product-detail">
                    <!-- Product Image -->
                    <div class="product-detail__gallery">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-detail__image">
                    </div>

                    <!-- Product Info -->
                    <div class="product-detail__info">
                        <h1>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        <p class="product-detail__price">
                            <?php echo formatPrice($product['price']); ?>
                        </p>

                        <div class="product-detail__description">
                            <p>
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                        </div>

                        <!-- Features -->
                        <?php if (isset($product['features']) && count($product['features']) > 0): ?>
                            <div class="product-detail__features">
                                <h3>Key Features</h3>
                                <ul class="product-detail__features-list">
                                    <?php foreach ($product['features'] as $feature): ?>
                                        <li class="product-detail__feature">
                                            <?php echo htmlspecialchars($feature); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Product Meta -->
                        <div style="margin-bottom: var(--space-6);">
                            <p><strong>Category:</strong>
                                <?php echo htmlspecialchars($product['category']); ?>
                            </p>
                            <p><strong>Brand:</strong>
                                <?php echo htmlspecialchars($product['brand']); ?>
                            </p>
                        </div>

                        <!-- Add to Cart Form -->
                        <form action="add_to_cart.php" method="POST" class="product-detail__actions">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn--primary btn--lg">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Add to Cart
                            </button>
                            <a href="products.php" class="btn btn--outline btn--lg">Continue Shopping</a>
                        </form>
                    </div>
                </div>
            </section>
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
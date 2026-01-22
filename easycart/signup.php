<?php
session_start();
require_once 'data.php';
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
    <title>Sign Up - EasyCart</title>
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
                        <li><a href="signup.php" class="nav__link nav__link--active">Signup</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <section class="section">
                <form class="form">
                    <h2 style="text-align: center; margin-bottom: var(--space-6);">Create Your Account</h2>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                        <div class="form__group">
                            <label class="form__label">First Name</label>
                            <input type="text" class="form__input" placeholder="John" required>
                        </div>
                        <div class="form__group">
                            <label class="form__label">Last Name</label>
                            <input type="text" class="form__input" placeholder="Doe" required>
                        </div>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Email Address</label>
                        <input type="email" class="form__input" placeholder="john@example.com" required>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Password</label>
                        <input type="password" class="form__input" placeholder="Create a password" required>
                    </div>
                    <div class="form__group">
                        <label class="form__label">Confirm Password</label>
                        <input type="password" class="form__input" placeholder="Confirm your password" required>
                    </div>
                    <div class="form__actions">
                        <button type="submit" class="btn btn--primary btn--full">Create Account</button>
                    </div>
                    <div style="text-align: center; margin-top: var(--space-4);">
                        <p>Already have an account? <a href="login.php" class="form__link">Login</a></p>
                    </div>
                </form>
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
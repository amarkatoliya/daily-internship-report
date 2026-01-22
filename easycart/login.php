<?php
session_start();
require_once 'data.php';
// Cart count logic
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
    <title>Login - EasyCart</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="auth-container">
        <!-- Visual Side (Left) -->
        <div class="auth-visual">
            <div class="auth-visual__content">
                <h1 class="auth-visual__title">Welcome Back.</h1>
                <p class="auth-visual__text">
                    Discover a world of premium products at unbeatable prices.
                    Join thousands of satisfied customers who trust EasyCart for their daily tech and lifestyle needs.
                </p>

                <div class="auth-visual__card">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg width=" 24" height="24" viewBox="0 0 24 24" fill="none" stroke="white"
                            stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                  
                        </div>
                        <div>
                            <div style="font-weight: bold; font-size: 1.1rem;">Verified & Secure</div>
                            <div style="font-size: 0.9rem; opacity: 0.8;">Shop with 100% confidence</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Side (Right) -->
        <div class="auth-form-side">
            <!-- Floating Navigation -->
            <div style="position: absolute; top: 2rem; right: 2rem; z-index: 10;">
                <a href="index.php" class="btn btn--outline btn--sm">Back to Home</a>
            </div>

            <div class="auth-form-container">
                <div class="auth-header">
                    <a href="index.php" class="auth-logo">EasyCart</a>
                    <h2 class="auth-title">Log in to your account</h2>
                    <p class="auth-subtitle">Welcome back! Please enter your details.</p>
                </div>

                <form class="form"
                    style="box-shadow: none; padding: 0; background: transparent; border: none; max-width: 100%;">
                    <div class="auth-input-group">
                        <label class="auth-label">Email</label>
                        <input type="email" class="auth-input" placeholder="Enter your email" required>
                    </div>

                    <div class="auth-input-group">
                        <label class="auth-label">Password</label>
                        <input type="password" class="auth-input" placeholder="••••••••" required>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <label
                            style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                            <input type="checkbox" style="accent-color: var(--color-primary);">
                            <span style="color: var(--text-secondary);">Remember me</span>
                        </label>
                        <a href="#"
                            style="color: var(--color-primary); font-size: 0.9rem; font-weight: 500; text-decoration: none;">Forgot
                            password?</a>
                    </div>

                    <button type="submit" class="btn btn--primary btn--full btn--lg">Sign In</button>

                    <nav class="auth-divider">
                        <span>OR CONTINUE WITH</span>
                    </nav>

                    <div class="social-buttons">
                        <button type="button" class="btn-social">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                            Facebook
                        </button>
                        <button type="button" class="btn-social">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                            Google
                        </button>
                    </div>

                    <div style="text-align: center; margin-top: 1.5rem;">
                        <span style="color: var(--text-secondary);">Don't have an account? </span>
                        <a href="signup.php"
                            style="color: var(--color-primary); font-weight: 600; text-decoration: none;">Sign up</a>
                    </div>
                </form>

                <footer class="footer--minimal" style="padding-top: 2rem; margin-top: 0; background: transparent;">
                    <div class="footer__bottom" style="border-top: none;">
                        <div class="footer__links-inline">
                            <a href="#" class="footer__link">Privacy</a>
                            <a href="#" class="footer__link">Terms</a>
                            <a href="#" class="footer__link">Help</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
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
    <title>Sign Up - EasyCart</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="auth-container">
        <!-- Visual Side (Left) -->
        <div class="auth-visual"
            style="background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));">
            <div class="auth-visual__content">
                <h1 class="auth-visual__title">Start Your Journey.</h1>
                <p class="auth-visual__text">
                    Join the EasyCart community today. Get exclusive access to flash sales, member-only discounts, and
                    personalized recommendations.
                </p>

                <div class="auth-visual__card">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div
                            style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: bold; font-size: 1.1rem;">10k+ Members</div>
                            <div style="font-size: 0.9rem; opacity: 0.8;">Join our growing family</div>
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
                    <h2 class="auth-title">Create your account</h2>
                    <p class="auth-subtitle">It's free and easy to get started.</p>
                </div>

                <form class="form"
                    style="box-shadow: none; padding: 0; background: transparent; border: none; max-width: 100%;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="auth-input-group">
                            <label class="auth-label">First Name</label>
                            <input type="text" class="auth-input" placeholder="John" required>
                        </div>
                        <div class="auth-input-group">
                            <label class="auth-label">Last Name</label>
                            <input type="text" class="auth-input" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label class="auth-label">Email</label>
                        <input type="email" class="auth-input" placeholder="john@example.com" required>
                    </div>

                    <div class="auth-input-group">
                        <label class="auth-label">Password</label>
                        <input type="password" class="auth-input" placeholder="Create a password" required>
                    </div>

                    <button type="submit" class="btn btn--primary btn--full btn--lg">Create Account</button>

                    <nav class="auth-divider">
                        <span>OR REGISTER WITH</span>
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
                        <span style="color: var(--text-secondary);">Already have an account? </span>
                        <a href="login.php"
                            style="color: var(--color-primary); font-weight: 600; text-decoration: none;">Log in</a>
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
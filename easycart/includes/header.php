<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <div class="container">
        <div class="header__content">
            <a href="index.php" class="header__logo">EasyCart</a>
            <nav class="header__nav">
                <ul class="nav__list">
                    <li><a href="index.php"
                            class="nav__link <?php echo $current_page == 'index.php' ? 'nav__link--active' : ''; ?>">Home</a>
                    </li>
                    <li><a href="plp.php"
                            class="nav__link <?php echo $current_page == 'plp.php' || $current_page == 'product.php' ? 'nav__link--active' : ''; ?>">Products</a>
                    </li>
                    <li><a href="cart.php"
                            class="nav__link <?php echo $current_page == 'cart.php' ? 'nav__link--active' : ''; ?>"
                            style="position: relative;">Cart
                            <?php if (isset($cartCount) && $cartCount > 0): ?>
                                <span class="cart-badge">
                                    <?php echo $cartCount; ?>
                                </span>
                            <?php endif; ?>
                        </a></li>
                    <li><a href="orders.php"
                            class="nav__link <?php echo $current_page == 'orders.php' ? 'nav__link--active' : ''; ?>">Orders</a>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav__user">
                            <span class="nav__link" style="color: var(--color-primary); font-weight: 600;">
                                Hi,
                                <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>
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
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
    header('Location: plp');
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
    <script src="js/add-to-cart.js" defer></script>
    <script src="js/product-gallery.js" defer></script>
</head>

<body>

    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <section class="section">
                <a href="plp" class="product-detail__back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M5 12l7-7m-7 7l7 7" />
                    </svg>
                    Back to Products
                </a>
            </section>

            <!-- Product Detail -->
            <section class="section">
                <div class="product-detail">
                    <!-- Product Image Gallery -->
                    <div class="product-gallery">
                        <div class="product-gallery__main">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainProductImage">
                        </div>
                        <?php if (isset($product['images']) && count($product['images']) > 1): ?>
                            <div class="product-gallery__thumbs">
                                <?php foreach ($product['images'] as $index => $imgUrl): ?>
                                    <div class="product-gallery__thumb <?php echo $imgUrl === $product['image_url'] ? 'active' : ''; ?>"
                                        onclick="switchProductImage(this, '<?php echo htmlspecialchars($imgUrl); ?>')">
                                        <img src="<?php echo htmlspecialchars($imgUrl); ?>"
                                            alt="Product angle <?php echo $index + 1; ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Info -->
                    <div class="product-detail__info">
                        <h1>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        <?php if (isset($product['shipping_type'])): ?>
                            <div style="margin-bottom: var(--space-3);">
                                <span
                                    class="shipping-badge shipping-badge--<?php echo strtolower($product['shipping_type']); ?>"
                                    style="position: relative; top: 0; left: 0;">
                                    <?php echo htmlspecialchars($product['shipping_type']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
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
                        <form action="add_to_cart" method="POST" class="product-detail__actions">
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
                            <a href="plp" class="btn btn--outline btn--lg">Continue Shopping</a>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>


    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>
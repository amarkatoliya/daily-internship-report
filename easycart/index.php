<?php
// Start session for cart functionality
session_start();

// Include data layer
require_once 'data.php';

// Get cart count for badge (filter invalid items)
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count(array_filter($_SESSION['cart'], function ($item) {
        return isset($item['id']) && $item['id'] > 0;
    }));
}

// Get first 6 products for featured section
$featuredProducts = array_slice($products, 0, 6);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="EasyCart - Your premier online shopping destination. Discover amazing products at unbeatable prices with fast, secure delivery.">
    <meta name="keywords" content="ecommerce, online shopping, electronics, gadgets, fashion, home appliances">
    <meta name="author" content="EasyCart">

    <title>EasyCart - Your Online Shopping Destination</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/add-to-cart.js" defer></script>
</head>

<body>

    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Hero Section -->
            <section class="section section--hero animate-fade-in-up">
                <div class="hero__container">
                    <div class="hero__content">
                        <div class="hero__badge">
                            <span class="hero__badge-text">✨ Your Premium Shopping Experience</span>
                        </div>

                        <h1 class="hero__title">
                            Discover Amazing Products at
                            <span class="hero__title-highlight">Unbeatable Prices</span>
                        </h1>

                        <p class="hero__subtitle">
                            Shop the latest trends, electronics, fashion, and home essentials all in one place.
                            Fast delivery, secure payments, and exceptional customer service.
                        </p>

                        <div class="hero__actions">
                            <a href="plp.php" class="hero__cta hero__cta--primary">
                                <span>Start Shopping</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <a href="#featured" class="hero__cta hero__cta--secondary">
                                <span>Explore Categories</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 14L12 21L5 14H9V3H15V14H19Z" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                        <div class="hero__stats">
                            <div class="hero__stat">
                                <div class="hero__stat-number">10K+</div>
                                <div class="hero__stat-label">Happy Customers</div>
                            </div>
                            <div class="hero__stat">
                                <div class="hero__stat-number"><?php echo count($products); ?>+</div>
                                <div class="hero__stat-label">Products</div>
                            </div>
                            <div class="hero__stat">
                                <div class="hero__stat-number">4.9★</div>
                                <div class="hero__stat-label">Rating</div>
                            </div>
                            <div class="hero__stat">
                                <div class="hero__stat-number">24/7</div>
                                <div class="hero__stat-label">Support</div>
                            </div>
                        </div>
                    </div>

                    <div class="hero__visual">
                        <div class="hero__image-container">
                            <div class="hero__image-main">
                                <img src="assets/images/laptop.jpg" alt="Premium Laptop" class="hero__image">
                                <div class="hero__image-badge">
                                    <span>Featured</span>
                                </div>
                            </div>
                            <div class="hero__floating-cards">
                                <div class="hero__floating-card hero__floating-card--1">
                                    <img src="assets/images/smartphone.jpg" alt="Smartphone"
                                        class="hero__floating-image">
                                </div>
                                <div class="hero__floating-card hero__floating-card--2">
                                    <img src="assets/images/headphones.jpg" alt="Headphones"
                                        class="hero__floating-image">
                                </div>
                                <div class="hero__floating-card hero__floating-card--3">
                                    <img src="assets/images/smartwatch.jpg" alt="Smartwatch"
                                        class="hero__floating-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero__background">
                    <div class="hero__bg-shape hero__bg-shape--1"></div>
                    <div class="hero__bg-shape hero__bg-shape--2"></div>
                    <div class="hero__bg-shape hero__bg-shape--3"></div>
                </div>

                <div class="hero__decorations">
                    <div class="hero__decoration hero__decoration--1">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L15.09 8.26L22 9L17 14L18.18 21L12 17.77L5.82 21L7 14L2 9L8.91 8.26L12 2Z"
                                fill="currentColor" opacity="0.1" />
                        </svg>
                    </div>
                    <div class="hero__decoration hero__decoration--2">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1" opacity="0.05" />
                        </svg>
                    </div>
                    <div class="hero__decoration hero__decoration--3">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L13.09 8.26L19 9L14 14L15.18 21L12 17.77L8.82 21L10 14L5 9L10.91 8.26L12 2Z"
                                fill="currentColor" opacity="0.08" />
                        </svg>
                    </div>
                </div>
            </section>

            <!-- Featured Products Section -->
            <section class="section">
                <h2 class="section__title">Featured Products</h2>
                <div class="products-grid animate-fade-in-up" style="animation-delay: 0.2s;">
                    <?php foreach ($featuredProducts as $product): ?>
                        <article class="product-card">
                            <div class="product-card__image">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                            </div>
                            <div class="product-card__content">
                                <h3 class="product-card__title">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>
                                <p class="product-card__price">
                                    <?php echo formatPrice($product['price']); ?>
                                </p>
                                <div class="product-card__actions">
                                    <a href="product.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn--primary btn--sm">View Details</a>
                                    <form action="add_to_cart.php" method="POST" style="flex-shrink: 0;">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn--outline btn--sm" aria-label="Add to cart">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M9 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                                <path d="M20 20a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Popular Categories Section -->
            <section id="featured" class="section">
                <h2 class="section__title">Popular Categories</h2>
                <div class="categories-grid">
                    <?php foreach ($categories as $category): ?>
                        <a href="plp.php?category=<?php echo urlencode($category['name']); ?>" class="category-card">
                            <div class="category-card__icon">
                                <?php echo $category['icon']; ?>
                            </div>
                            <h3 class="category-card__title">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Popular Brands Section -->
            <section class="section">
                <h2 class="section__title">Popular Brands</h2>
                <div class="categories-grid">
                    <?php foreach ($brands as $brand): ?>
                        <a href="plp.php?brand=<?php echo urlencode($brand['name']); ?>" class="category-card">
                            <div class="category-card__icon">
                                <?php echo $brand['logo']; ?>
                            </div>
                            <h3 class="category-card__title">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>


    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>
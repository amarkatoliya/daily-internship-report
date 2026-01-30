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

// Get filter parameters
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : 'all';

// Pagination configuration
$productsPerPage = 3;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
// var_dump($currentPage); 
$currentPage = max(1, $currentPage); // Ensure page is at least 1

// Filter products
if ($categoryFilter !== 'all') {
    $displayProducts = getProductsByCategory($categoryFilter);
} elseif ($brandFilter !== 'all') {
    $displayProducts = getProductsByBrand($brandFilter);
} else {
    $displayProducts = $products;
}

// Calculate pagination
$totalProducts = count($displayProducts);
$totalPages = ceil($totalProducts / $productsPerPage);
$currentPage = min($currentPage, max(1, $totalPages)); // Ensure page doesn't exceed total

// Get products for current page
$offset = ($currentPage - 1) * $productsPerPage;
$productsToDisplay = array_slice($displayProducts, $offset, $productsPerPage);

// Page title based on filter
$pageTitle = 'All Products';
if ($categoryFilter !== 'all') {
    $pageTitle = 'Category: ' . htmlspecialchars($categoryFilter);
} elseif ($brandFilter !== 'all') {
    $pageTitle = 'Brand: ' . htmlspecialchars($brandFilter);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $pageTitle; ?> - EasyCart
    </title>
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
            <!-- Page Heading -->
            <section class="section">
                <h2 class="section__title">
                    <?php echo $pageTitle; ?>
                </h2>
                <p class="text-secondary">Showing
                    <?php echo $offset + 1; ?>-<?php echo min($offset + $productsPerPage, $totalProducts); ?>
                    of <?php echo $totalProducts; ?> products
                </p>
            </section>

            <!-- Products Grid -->
            <section class="section">
                <div class="products-grid animate-fade-in-up">
                    <?php if (count($productsToDisplay) > 0): ?>
                        <?php foreach ($productsToDisplay as $product): ?>
                            <article class="product-card">
                                <div class="product-card__image">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                    <?php if (isset($product['shipping_type'])): ?>
                                        <span
                                            class="shipping-badge shipping-badge--<?php echo strtolower($product['shipping_type']); ?>">
                                            <?php echo htmlspecialchars($product['shipping_type']); ?>
                                        </span>
                                    <?php endif; ?>
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
                    <?php else: ?>
                        <p>No products found in this category.</p>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <!-- Previous Button -->
                        <?php if ($currentPage > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>"
                                class="pagination__btn pagination__btn--prev">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                Previous
                            </a>
                        <?php else: ?>
                            <span class="pagination__btn pagination__btn--prev pagination__btn--disabled">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                                Previous
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <div class="pagination__numbers">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $currentPage): ?>
                                    <span class="pagination__number pagination__number--active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"
                                        class="pagination__number"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Next Button -->
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>"
                                class="pagination__btn pagination__btn--next">
                                Next
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="pagination__btn pagination__btn--next pagination__btn--disabled">
                                Next
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>


    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>
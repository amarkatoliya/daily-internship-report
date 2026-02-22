<?php
/**
 * EasyCart Data Layer Hub (PostgreSQL)
 * Provides the same helper functions as before, backed by DB queries.
 * All functions return arrays in the same format as the old static data,
 * so template files don't need changes.
 */

require_once __DIR__ . '/Database/db.php';

// ============================================================
// PRODUCT FUNCTIONS
// ============================================================

/**
 * Get all products with category name
 * Returns array identical to old $products array
 */
function getAllProducts()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT 
            p.entity_id AS id,
            p.sku,
            p.name,
            p.price,
            p.brand,
            p.shipping_type,
            p.image AS image_url,
            c.name AS category
        FROM catalog_product_entity p
        LEFT JOIN catalog_category_product cp ON p.entity_id = cp.product_id
        LEFT JOIN catalog_category_entity c ON cp.category_id = c.entity_id
        ORDER BY p.entity_id
    ");
    return $stmt->fetchAll();
}

/**
 * Get product by ID (with description, features, gallery images)
 * Returns array identical to old getProductById()
 */
function getProductById($id)
{
    global $pdo;

    // Get main product info
    $stmt = $pdo->prepare("
        SELECT 
            p.entity_id AS id,
            p.sku,
            p.name,
            p.price,
            p.brand,
            p.shipping_type,
            p.image AS image_url,
            c.name AS category
        FROM catalog_product_entity p
        LEFT JOIN catalog_category_product cp ON p.entity_id = cp.product_id
        LEFT JOIN catalog_category_entity c ON cp.category_id = c.entity_id
        WHERE p.entity_id = :id
    ");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        return null;
    }

    // Get description
    $stmt = $pdo->prepare("
        SELECT value FROM catalog_product_attribute 
        WHERE product_id = :id AND attribute_code = 'description'
        LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    $desc = $stmt->fetch();
    $product['description'] = $desc ? $desc['value'] : '';

    // Get features
    $stmt = $pdo->prepare("
        SELECT value FROM catalog_product_attribute 
        WHERE product_id = :id AND attribute_code = 'feature'
    ");
    $stmt->execute(['id' => $id]);
    $product['features'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get gallery images
    $stmt = $pdo->prepare("
        SELECT value FROM catalog_product_attribute 
        WHERE product_id = :id AND attribute_code = 'gallery_item'
    ");
    $stmt->execute(['id' => $id]);
    $product['images'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $product;
}

/**
 * Get products by category name
 */
function getProductsByCategory($category)
{
    global $pdo;
    if ($category === 'all' || empty($category)) {
        return getAllProducts();
    }
    $stmt = $pdo->prepare("
        SELECT 
            p.entity_id AS id,
            p.sku,
            p.name,
            p.price,
            p.brand,
            p.shipping_type,
            p.image AS image_url,
            c.name AS category
        FROM catalog_product_entity p
        LEFT JOIN catalog_category_product cp ON p.entity_id = cp.product_id
        LEFT JOIN catalog_category_entity c ON cp.category_id = c.entity_id
        WHERE LOWER(c.name) = LOWER(:category)
        ORDER BY p.entity_id
    ");
    $stmt->execute(['category' => $category]);
    return $stmt->fetchAll();
}

/**
 * Get products by brand name
 */
function getProductsByBrand($brand)
{
    global $pdo;
    if ($brand === 'all' || empty($brand)) {
        return getAllProducts();
    }
    $stmt = $pdo->prepare("
        SELECT 
            p.entity_id AS id,
            p.sku,
            p.name,
            p.price,
            p.brand,
            p.shipping_type,
            p.image AS image_url,
            c.name AS category
        FROM catalog_product_entity p
        LEFT JOIN catalog_category_product cp ON p.entity_id = cp.product_id
        LEFT JOIN catalog_category_entity c ON cp.category_id = c.entity_id
        WHERE LOWER(p.brand) = LOWER(:brand)
        ORDER BY p.entity_id
    ");
    $stmt->execute(['brand' => $brand]);
    return $stmt->fetchAll();
}

// ============================================================
// CATEGORY FUNCTIONS
// ============================================================

/**
 * Get all categories with icons
 * Returns array matching old $categories format: [{name, icon}, ...]
 */
function getAllCategories()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT 
            c.entity_id AS id,
            c.name,
            COALESCE(a.value, '') AS icon
        FROM catalog_category_entity c
        LEFT JOIN catalog_category_attribute a 
            ON c.entity_id = a.category_id AND a.attribute_code = 'icon'
        ORDER BY c.entity_id
    ");
    return $stmt->fetchAll();
}

// ============================================================
// BRAND FUNCTIONS
// ============================================================

/**
 * Get all unique brands
 * Returns array matching old $brands format: [{name, logo}, ...]
 */
function getAllBrands()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT DISTINCT brand AS name
        FROM catalog_product_entity
        WHERE brand IS NOT NULL AND brand != ''
        ORDER BY brand
    ");
    $brands = $stmt->fetchAll();

    // Add a default SVG icon for each brand (since logos aren't stored in EAV)
    foreach ($brands as &$brand) {
        $brand['logo'] = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>';
    }
    unset($brand);

    return $brands;
}

// ============================================================
// UTILITY FUNCTIONS
// ============================================================

/**
 * Format price in Indian Rupees
 */
function formatPrice($price)
{
    return '₹' . number_format($price, 2, '.', ',');
}

// ============================================================
// PRE-LOAD DATA (for backward compatibility)
// Files like index.php use $products, $categories, $brands directly
// ============================================================
$products = getAllProducts();
$categories = getAllCategories();
$brands = getAllBrands();

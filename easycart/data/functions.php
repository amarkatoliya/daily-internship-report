<?php
/**
 * Helper function to get product by ID
 */
function getProductById($id)
{
    global $products;
    foreach ($products as $product) {
        if ($product['id'] == $id) {
            return $product;
        }
    }
    return null;
}

/**
 * Helper function to get products by category
 */
function getProductsByCategory($category)
{
    global $products;
    if ($category === 'all' || empty($category)) {
        return $products;
    }
    return array_filter($products, function ($product) use ($category) {
        return strtolower($product['category']) === strtolower($category);
    });
}

/**
 * Helper function to get products by brand
 */
function getProductsByBrand($brand)
{
    global $products;
    if ($brand === 'all' || empty($brand)) {
        return $products;
    }
    return array_filter($products, function ($product) use ($brand) {
        return strtolower($product['brand']) === strtolower($brand);
    });
}

/**
 * Helper function to format price in Indian Rupees
 */
function formatPrice($price)
{
    return '₹' . number_format($price, 0, '.', ',');
}

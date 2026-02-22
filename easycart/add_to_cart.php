<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];

    if ($productId > 0) {
        // Initialize cart if not exists
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if (isset($item['id']) && $item['id'] == $productId) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        // If not found, add new item
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'quantity' => 1
            ];
        }

        // Return JSON if AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            require_once 'data.php';
            $cartCount = count(array_filter($_SESSION['cart'], function ($item) {
                return isset($item['id']) && $item['id'] > 0;
            }));

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => $cartCount
            ]);
            exit;
        }
    }
}

// Redirect to cart page for standard form submissions
header('Location: cart');
exit;
?>
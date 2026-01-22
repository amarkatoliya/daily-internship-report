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
    }
}

// Redirect to cart page
header('Location: cart.php');
exit;
?>
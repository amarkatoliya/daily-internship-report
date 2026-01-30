<?php
session_start();
require_once 'data.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$action = $_POST['action'] ?? '';
$productId = (int) ($_POST['product_id'] ?? 0);

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

if ($action === 'update') {
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $delta = (int) ($_POST['delta'] ?? 0);
    $shouldRemove = false;

    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['id']) && $item['id'] === $productId) {
            if ($delta !== 0) {
                $item['quantity'] += $delta;
            } else {
                $item['quantity'] = $quantity;
            }

            if ($item['quantity'] < 1) {
                $shouldRemove = true;
            } else {
                if ($item['quantity'] > 99)
                    $item['quantity'] = 99;
                $newQuantity = $item['quantity'];
            }
            break;
        }
    }
    unset($item);

    if ($shouldRemove) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
            return $item['id'] !== $productId;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        $action = 'remove'; // Set action to remove so isRemoved is true in response
    }
} elseif ($action === 'remove') {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
            return $item['id'] !== $productId;
        });
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Calculate new totals
$subtotal = 0;
$totalItemsCount = 0;
$totalQuantity = 0;
$itemTotal = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductById($item['id']);
        if ($product) {
            $currentTotal = $product['price'] * $item['quantity'];
            $subtotal += $currentTotal;
            $totalItemsCount++;
            $totalQuantity += $item['quantity'];
            if ((int) $item['id'] === (int) $productId) {
                $itemTotal = $currentTotal;
            }
        }
    }
}

// Shipping calculated at checkout, so 0 for cart page summary
$shipping = 0;
$total = $subtotal;

echo json_encode([
    'success' => true,
    'subtotal' => formatPrice($subtotal),
    'shipping' => formatPrice($shipping),
    'total' => formatPrice($total),
    'cartCount' => $totalItemsCount,
    'totalQuantity' => $totalQuantity,
    'itemTotal' => formatPrice($itemTotal),
    'newQuantity' => $newQuantity ?? 0,
    'isRemoved' => ($action === 'remove')
]);

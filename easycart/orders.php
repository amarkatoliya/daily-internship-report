<?php
// Start session
session_start();

// Protection: Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Please log in to view your orders.";
    header("Location: login");
    exit();
}

// Include data layer
require_once 'data.php';

// Check for order success message
$orderSuccess = isset($_SESSION['order_success']) && $_SESSION['order_success'];
if ($orderSuccess) {
    unset($_SESSION['order_success']);
}

// Static orders array (simulating past orders)
$staticOrders = [
    [
        'id' => 'ORD-2026-001',
        'date' => '2026-01-15',
        'items' => 3,
        'total' => 33996,
        'status' => 'Delivered',
        'tracking' => 'TRACK1234567890'
    ],
    [
        'id' => 'ORD-2026-002',
        'date' => '2026-01-18',
        'items' => 2,
        'total' => 23498,
        'status' => 'In Transit',
        'tracking' => 'TRACK0987654321'
    ],
    [
        'id' => 'ORD-2026-003',
        'date' => '2026-01-20',
        'items' => 1,
        'total' => 90499,
        'status' => 'Processing',
        'tracking' => 'TRACK1122334455'
    ]
];

// Load orders from persistent JSON storage
$ordersFile = 'data/orders.json';
$orders = $staticOrders; // Initialize with static orders
$allPersistentOrders = [];
if (file_exists($ordersFile)) {
    $jsonContent = file_get_contents($ordersFile);
    $allPersistentOrders = json_decode($jsonContent, true) ?: [];
}

// Filter orders for the logged-in user and format them for display
$userEmail = $_SESSION['user']['email'];
foreach ($allPersistentOrders as $persistentOrder) {
    if (isset($persistentOrder['customer']['email']) && $persistentOrder['customer']['email'] === $userEmail) {
        $orders[] = [
            'id' => $persistentOrder['id'],
            'date' => date('Y-m-d', strtotime($persistentOrder['date'])),
            'items' => count($persistentOrder['items']),
            'total' => $persistentOrder['total'],
            'status' => ucfirst($persistentOrder['status']),
            'tracking' => 'TRACK' . substr(md5($persistentOrder['id']), 0, 10) // Mock tracking ID based on order ID
        ];
    }
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - EasyCart</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <?php if ($orderSuccess): ?>
                <section class="section">
                    <div
                        style="background: var(--color-success-light); border: 1px solid var(--color-success); padding: var(--space-6); border-radius: var(--radius-lg); text-align: center;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)"
                            stroke-width="2" style="margin: 0 auto var(--space-4);">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <h3
                            style="font-size: var(--font-size-2xl); color: var(--color-success); margin-bottom: var(--space-2);">
                            Order Placed Successfully!</h3>
                        <p style="color: var(--text-secondary);">Thank you for your order. We'll send you a confirmation
                            email shortly.</p>
                    </div>
                </section>
            <?php endif; ?>

            <section class="section">
                <h2 class="section__title">My Orders</h2>
                <p class="text-secondary">View and track your orders</p>
            </section>

            <section class="section">
                <div class="table">
                    <div class="table__header">
                        <div class="table__row"
                            style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; gap: var(--space-4);">
                            <div class="table__cell table__cell--header">Order ID</div>
                            <div class="table__cell table__cell--header">Date</div>
                            <div class="table__cell table__cell--header">Items</div>
                            <div class="table__cell table__cell--header">Total</div>
                            <div class="table__cell table__cell--header">Status</div>
                            <div class="table__cell table__cell--header">Tracking</div>
                        </div>
                    </div>
                    <div class="table__body">
                        <?php foreach ($orders as $order): ?>
                            <div class="table__row"
                                style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; gap: var(--space-4);">
                                <div class="table__cell"><strong>
                                        <?php echo htmlspecialchars($order['id']); ?>
                                    </strong></div>
                                <div class="table__cell">
                                    <?php echo date('M d, Y', strtotime($order['date'])); ?>
                                </div>
                                <div class="table__cell">
                                    <?php echo $order['items']; ?> items
                                </div>
                                <div class="table__cell table__cell--price">
                                    <?php echo formatPrice($order['total']); ?>
                                </div>
                                <div class="table__cell">
                                    <?php
                                    $statusClass = '';
                                    switch ($order['status']) {
                                        case 'Delivered':
                                            $statusClass = 'table__cell--status--delivered';
                                            break;
                                        case 'In Transit':
                                            $statusClass = 'table__cell--status--in-transit';
                                            break;
                                        case 'Processing':
                                            $statusClass = 'table__cell--status--processing';
                                            break;
                                    }
                                    ?>
                                    <span class="table__cell--status <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                                <div class="table__cell">
                                    <a href="#"
                                        style="color: var(--color-primary); text-decoration: none; font-weight: 500;">
                                        <?php echo htmlspecialchars($order['tracking']); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-top: var(--space-8); text-align: center;">
                    <a href="plp" class="btn btn--primary btn--lg">Continue Shopping</a>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>
<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$orderId = (int)($_GET['id'] ?? 0);
$orderRepository = new OrderRepository();
$order = $orderRepository->getOrderById($orderId);
$items = $orderRepository->getOrderItems($orderId);
if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . '/myorders');
    exit;
}

// Ensure cart is cleared for this user after viewing order confirmation
// This is a backup in case cart wasn't cleared during order placement
try {
    $cartService = new CartService();
    $cart = $cartService->getCart($_SESSION['user_id']);
    // Only clear if cart has items (to avoid unnecessary operations)
    if (!empty($cart['items'])) {
        $cartService->clearCart($_SESSION['user_id']);
    }
} catch (Exception $e) {
    // Silently fail - cart clearing is not critical at this point
    error_log('Failed to clear cart on order confirmation page: ' . $e->getMessage());
}

// Check if loyalty points were awarded for this order
$loyaltyService = new LoyaltyService();
$pdo = Database::connect();
$pointsStmt = $pdo->prepare("SELECT points FROM loyalty_transactions WHERE order_id = ? AND user_id = ? AND transaction_type = 'earned' ORDER BY created_at DESC LIMIT 1");
$pointsStmt->execute([$orderId, $_SESSION['user_id']]);
$pointsTransaction = $pointsStmt->fetch(PDO::FETCH_ASSOC);
$pointsAwarded = $pointsTransaction ? (int)$pointsTransaction['points'] : 0;

// If no points found, calculate what should have been awarded (in case points weren't awarded yet)
if ($pointsAwarded === 0) {
    $expectedPoints = (int)floor($order['subtotal'] / 10);
    // Try to award points if they weren't awarded yet
    if ($expectedPoints > 0) {
        try {
            $loyaltyService->awardPointsForOrder($_SESSION['user_id'], $order['subtotal'], $orderId);
            // Re-check
            $pointsStmt->execute([$orderId, $_SESSION['user_id']]);
            $pointsTransaction = $pointsStmt->fetch(PDO::FETCH_ASSOC);
            $pointsAwarded = $pointsTransaction ? (int)$pointsTransaction['points'] : $expectedPoints;
        } catch (Exception $e) {
            // If awarding fails, just show expected points
            $pointsAwarded = $expectedPoints;
        }
    }
}

renderHeader();
?>

<style>
    :root {
        --white: #FFFFFF;
        --dark: #1A1A1A;
        --divider: #E5E5E5;
        --teal-600: #00B8D4;
        --teal-light: #DDFAFF;
        --success: #22C55E;
        --muted: #666666;
        --card-radius: 16px;
    }

    .confirmation-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .success-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: var(--teal-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 48px;
        color: var(--success);
    }

    .success-header h1 {
        font-size: 32px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .success-header p {
        font-size: 18px;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .tracking-id {
        display: inline-block;
        padding: 12px 24px;
        background: var(--teal-light);
        border-radius: 12px;
        font-size: 20px;
        font-weight: 700;
        color: var(--teal-600);
        margin-top: 12px;
    }

    .order-details-card {
        background: var(--white);
        border: 1px solid var(--divider);
        border-radius: var(--card-radius);
        padding: 30px;
        margin-bottom: 24px;
    }

    .order-details-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--divider);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--divider);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--dark);
    }

    .detail-value {
        color: var(--muted);
    }

    .items-section {
        margin-top: 24px;
    }

    .items-section h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 16px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: var(--teal-light);
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
    }

    .item-details {
        font-size: 14px;
        color: var(--muted);
    }

    .item-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--teal-600);
    }

    .summary-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid var(--divider);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 16px;
    }

    .summary-row.total {
        font-size: 24px;
        font-weight: 800;
        color: var(--dark);
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid var(--divider);
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 40px;
    }

    .btn {
        padding: 16px 32px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--teal-600);
        color: white;
    }

    .btn-primary:hover {
        background: #00aebc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 184, 212, 0.3);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--teal-600);
        border: 2px solid var(--teal-600);
    }

    .btn-secondary:hover {
        background: var(--teal-light);
    }

    .loyalty-notification {
        background: linear-gradient(135deg, var(--teal-600) 0%, #00aebc 100%);
        border-radius: var(--card-radius);
        padding: 24px 30px;
        margin-bottom: 24px;
        color: white;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 184, 212, 0.3);
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loyalty-notification h3 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 12px;
        color: white;
    }

    .loyalty-notification .points-amount {
        font-size: 48px;
        font-weight: 800;
        margin: 16px 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .loyalty-notification p {
        font-size: 16px;
        margin-bottom: 20px;
        opacity: 0.95;
    }

    .loyalty-notification .loyalty-btn {
        display: inline-block;
        background: white;
        color: var(--teal-600);
        padding: 14px 32px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .loyalty-notification .loyalty-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        background: var(--teal-light);
    }

    .loyalty-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }
</style>

<div class="confirmation-container">
    <div class="success-header">
        <div class="success-icon">✓</div>
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your purchase. Your order has been confirmed.</p>
        <div class="tracking-id">
            Order ID: <?= htmlspecialchars($order['order_number'] ?? 'N/A') ?>
        </div>
    </div>

    <?php if ($pointsAwarded > 0): ?>
    <div class="loyalty-notification">
        <div class="loyalty-icon">🎁</div>
        <h3>You've Earned Loyalty Points!</h3>
        <div class="points-amount">+<?= number_format($pointsAwarded) ?> Points</div>
        <p>Great news! You've earned <strong><?= number_format($pointsAwarded) ?> loyalty points</strong> from this purchase.</p>
        <p style="font-size: 14px; opacity: 0.9;">Points are calculated as 1 point per 10 EGP spent (based on subtotal)</p>
        <a href="<?= BASE_URL ?>/loyality" class="loyalty-btn">View My Loyalty Points →</a>
    </div>
    <?php endif; ?>

    <div class="order-details-card">
        <h2>Order Details</h2>
        
        <div class="detail-row">
            <span class="detail-label">Order Number:</span>
            <span class="detail-value"><?= htmlspecialchars($order['order_number'] ?? 'N/A') ?></span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Order Date:</span>
            <span class="detail-value">
                <?php
                $orderDate = $order['order_date'] ?? $order['created_at'] ?? date('Y-m-d H:i:s');
                try {
                    $date = new DateTime($orderDate);
                    echo $date->format('F d, Y \a\t g:i A');
                } catch (Exception $e) {
                    echo date('F d, Y \a\t g:i A');
                }
                ?>
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span class="detail-value" style="color: var(--teal-600); font-weight: 600;">
                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $order['order_status'] ?? 'Pending'))) ?>
            </span>
        </div>

        <?php if (!empty($order['area']) || !empty($order['street'])): ?>
        <div class="detail-row">
            <span class="detail-label">Shipping Address:</span>
            <span class="detail-value">
                <?php
                $addressParts = array_filter([
                    $order['area'] ?? '',
                    $order['street'] ?? '',
                    $order['building_name'] ?? ''
                ]);
                echo htmlspecialchars(implode(', ', $addressParts));
                ?>
            </span>
        </div>
        <?php endif; ?>

        <div class="items-section">
            <h3>Order Items (<?= count($items) ?> item<?= count($items) > 1 ? 's' : '' ?>)</h3>
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <div class="item-info">
                        <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                        <div class="item-details">
                            Size: <?= htmlspecialchars($item['size']) ?> | 
                            Quantity: <?= $item['quantity'] ?> | 
                            Unit Price: EGP <?= number_format($item['unit_price'], 2) ?>
                        </div>
                    </div>
                    <div class="item-price">EGP <?= number_format($item['total_price'], 2) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>EGP <?= number_format($order['subtotal'] ?? 0, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>EGP <?= number_format($order['shipping_fees'] ?? 40.00, 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Service Fee:</span>
                <span>EGP <?= number_format($order['service_fees'] ?? 10.00, 2) ?></span>
            </div>
            <?php if (($order['discount_amount'] ?? 0) > 0): ?>
            <div class="summary-row" style="color: var(--success);">
                <span>Discount:</span>
                <span>-EGP <?= number_format($order['discount_amount'], 2) ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span>EGP <?= number_format($order['total_amount'] ?? 0, 2) ?></span>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="<?= BASE_URL ?>/order/myorders" class="btn btn-primary">My Orders</a>
        <a href="<?= BASE_URL ?>/shop" class="btn btn-secondary">Continue Shopping</a>
    </div>
</div>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

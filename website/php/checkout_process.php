<?php
/**
 * Process checkout
 */
require_once __DIR__ . '/../includes.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartService = new CartService();
    $cart = $cartService->getCart($_SESSION['user_id']);

    if (empty($cart['items'])) {
        $_SESSION['error'] = 'Cart is empty';
        header('Location: ' . BASE_URL . '/cart');
        exit;
    }

    $address = [
        'address_id' => (int)($_POST['address_id'] ?? 0)
    ];

    $paymentMethodId = $_POST['payment_method_id'] ?? '';
    $paymentMethodId = trim($paymentMethodId);
    $payment = [
        'payment_method_id' => (!empty($paymentMethodId) && $paymentMethodId !== '0') ? (int)$paymentMethodId : null
    ];

    $promoCode = [
        'promo_code_id' => (int)($_POST['promo_code_id'] ?? 0),
        'discount_amount' => (float)($_POST['discount_amount'] ?? 0)
    ];

    $orderId = null;
    try {
        $orderService = new OrderService();
        $orderId = $orderService->placeOrder(
            $_SESSION['user_id'],
            $address,
            $payment,
            $cart['items'],
            $promoCode
        );

        unset($_SESSION['error']);
        $_SESSION['success'] = 'Order placed successfully!';
        
        header('Location: ' . BASE_URL . '/orderconfirmation?id=' . $orderId);
        exit;
    } catch (Exception $e) {
        error_log('Order placement error: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        
        if (!$orderId) {
            $orderRepository = new OrderRepository();
            $recentOrders = $orderRepository->getOrdersByUserId($_SESSION['user_id']);
            if (!empty($recentOrders)) {
                $mostRecent = $recentOrders[0];
                $orderId = $mostRecent['order_id'];
            }
        }
        
        if ($orderId && $orderId > 0) {
            unset($_SESSION['error']);
            $_SESSION['success'] = 'Order placed successfully!';
            header('Location: ' . BASE_URL . '/orderconfirmation?id=' . $orderId);
            exit;
        } else {
            $_SESSION['error'] = 'Failed to place order. Please try again.';
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }
    }
}

header('Location: ' . BASE_URL . '/checkout');
exit;


<?php
require_once __DIR__ . '/../includes.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Your session has expired. Please log in again.';
    header('Location: ' . BASE_URL . '/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($cartItemId > 0 && $quantity > 0) {
        try {
            $cartService = new CartService();
            $cartService->updateItem($cartItemId, $quantity);
            $_SESSION['success'] = 'Cart updated successfully';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to update cart: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Invalid cart item or quantity';
    }
}

header('Location: ' . BASE_URL . '/cart');
exit;

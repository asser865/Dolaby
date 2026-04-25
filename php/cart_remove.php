<?php
require_once __DIR__ . '/../includes.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Your session has expired. Please log in again.';
    header('Location: ' . BASE_URL . '/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
    $cartService = new CartService();
    $cartService->removeItem($cartItemId);
}

header('Location: ' . BASE_URL . '/cart');
exit;

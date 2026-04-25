<?php
require_once __DIR__ . '/../includes.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Your session has expired. Please log in again.';
    header('Location: ' . BASE_URL . '/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $size = $_POST['size'] ?? 'M';
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    try {
        $cartService = new CartService();
        $cartService->addItem($_SESSION['user_id'], $productId, $size, $quantity);
        $_SESSION['success'] = 'Item added to cart';
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

header('Location: ' . BASE_URL . '/cart');
exit;

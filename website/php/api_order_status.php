<?php
/**
 * Internal API to check order status
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../includes.php';

// Check if user is logged in (Session) OR provided via request (Simulated Token for Postman)
$user_id = $_SESSION['user_id'] ?? ($_GET['user_id'] ?? null);

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login or provide a user_id.']);
    exit;
}

// Get order ID from the query string
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit;
}

// Query the database
$orderRepo = new OrderRepository();
$order = $orderRepo->getOrderById($order_id);

// Ensure the order exists and belongs to the logged-in user
// Ensure the order exists and belongs to the user
if ($order && $order['user_id'] == $user_id) {
    echo json_encode([
        'success' => true, 
        'order_id' => $order['order_id'],
        'status' => $order['order_status']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
}
exit;

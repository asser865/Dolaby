<?php
/**
 * Internal API to UPDATE order status
 * Designed for testing via Postman
 */
require_once __DIR__ . '/../includes.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

// Get JSON or Form Data
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

// If they sent raw JSON via Postman
if ($order_id === 0 && empty($status)) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (is_array($data)) {
        $order_id = isset($data['order_id']) ? (int)$data['order_id'] : 0;
        $status = isset($data['status']) ? trim($data['status']) : '';
    }
}

if ($order_id <= 0 || empty($status)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing order_id or status']);
    exit;
}

// Allowed statuses
$validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
if (!in_array(strtolower($status), $validStatuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status. Use: ' . implode(', ', $validStatuses)]);
    exit;
}

// Update the database
$orderRepo = new OrderRepository();
$updated = $orderRepo->updateOrderStatus($order_id, strtolower($status));

if ($updated) {
    echo json_encode([
        'success' => true, 
        'message' => 'Order status updated successfully',
        'order_id' => $order_id,
        'new_status' => strtolower($status)
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
}
exit;

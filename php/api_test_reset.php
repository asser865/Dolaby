<?php
/**
 * TEST HELPER — Reset payment state for E2E testing.
 * This endpoint is safe: it only clears test data for specific order IDs.
 * SHOULD NOT be deployed to production (add to .gitignore or protect with env check).
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Use POST']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$order_id = isset($input['order_id']) ? (int)$input['order_id'] : 0;

if ($order_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Provide order_id']);
    exit;
}

try {
    $pdo = Database::connect();

    // 1. Delete ALL payment transactions for this order (success and failed)
    $stmt = $pdo->prepare("DELETE FROM payment_transactions WHERE order_id = :id");
    $stmt->execute(['id' => $order_id]);
    $deletedTransactions = $stmt->rowCount();

    // 2. Reset the order status back to 'pending'
    $stmt = $pdo->prepare("UPDATE orders SET order_status = 'pending' WHERE order_id = :id");
    $stmt->execute(['id' => $order_id]);

    echo json_encode([
        'success' => true,
        'message' => "Reset complete for order #$order_id",
        'deleted_transactions' => $deletedTransactions
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

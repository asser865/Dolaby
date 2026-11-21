<?php
/**
 * GET /api/loyalty/get_transactions.php?limit=N
 * Returns the loyalty transaction history for the logged-in customer.
 * For Postman testing, pass ?user_id=N.
 */
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../includes.php';

$user_id = $_SESSION['user_id'] ?? ($_GET['user_id'] ?? null);
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Login or pass user_id.']);
    exit;
}

$limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 10;

try {
    $loyaltyService = new LoyaltyService();
    $transactions   = $loyaltyService->getTransactions((int)$user_id, $limit);

    echo json_encode([
        'success' => true,
        'data'    => [
            'transactions' => $transactions,
            'count'        => count($transactions),
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

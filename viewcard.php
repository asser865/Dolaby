<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$cardId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($cardId <= 0) {
    $_SESSION['error'] = 'Invalid card ID';
    header('Location: ' . BASE_URL . '/paymentmethod');
    exit;
}

$paymentMethodRepository = new PaymentMethodRepository();
$card = $paymentMethodRepository->getById($cardId, $_SESSION['user_id']);

if (!$card) {
    $_SESSION['error'] = 'Card not found';
    header('Location: ' . BASE_URL . '/paymentmethod');
    exit;
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
    --font-montserrat: 'Montserrat', sans-serif;
  }

  .view-card-page {
    max-width: 700px;
    margin: 0 auto;
    padding: 40px 20px;
  }

  .topbar {
    width: 100%;
    padding: 18px 0;
    border-bottom: 1px solid var(--divider);
    margin-bottom: 30px;
  }

  .topbar .container {
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .top-left {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .back {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .back:hover {
    background: var(--teal-light);
    border-color: var(--teal-600);
  }

  .back svg {
    width: 20px;
    height: 20px;
    stroke: var(--dark);
  }

  .title {
    font-size: 22px;
    font-weight: 800;
    color: var(--dark);
    font-family: var(--font-montserrat);
  }

  .card-details-card {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 20px;
  }

  .detail-row {
    display: flex;
    padding: 16px 0;
    border-bottom: 1px solid var(--divider);
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-weight: 600;
    color: var(--dark);
    min-width: 150px;
    font-size: 15px;
  }

  .detail-value {
    color: #555555;
    font-size: 15px;
    flex: 1;
  }

  .default-badge {
    display: inline-block;
    background: var(--teal-light);
    color: var(--teal-600);
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    margin-left: 10px;
  }

  .card-number-display {
    font-family: 'Courier New', monospace;
    font-size: 18px;
    letter-spacing: 2px;
  }

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }
    .title {
      font-size: 20px;
    }
    .card-details-card {
      padding: 20px;
    }
    .detail-row {
      flex-direction: column;
      gap: 8px;
    }
    .detail-label {
      min-width: auto;
    }
  }
</style>

<div class="view-card-page">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/account/paymentmethod" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Card Details</div>
      </div>
    </div>
  </div>

  <!-- CARD DETAILS -->
  <div class="card-details-card">
    <div class="detail-row">
      <div class="detail-label">Card Type:</div>
      <div class="detail-value">
        <?= htmlspecialchars($card['card_type']) ?>
        <?php if ($card['is_default']): ?>
          <span class="default-badge">Default</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Card Number:</div>
      <div class="detail-value card-number-display">•••• <?= htmlspecialchars($card['last_four_digits']) ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Cardholder Name:</div>
      <div class="detail-value"><?= htmlspecialchars($card['cardholder_name'] ?? '') ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Expiry Date:</div>
      <div class="detail-value">
        <?= str_pad($card['expiry_month'], 2, '0', STR_PAD_LEFT) ?>/<?= $card['expiry_year'] ?>
      </div>
    </div>
  </div>
</div>

</body>
</html>


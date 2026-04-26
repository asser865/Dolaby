<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$paymentMethodRepository = new PaymentMethodRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_payment_id'])) {
    $paymentId = (int)$_POST['delete_payment_id'];
    $paymentMethodRepository->deleteById($paymentId, $_SESSION['user_id']);
    $_SESSION['success'] = 'Card removed successfully';
    header('Location: ' . BASE_URL . '/paymentmethod');
    exit;
}

$paymentMethods = $paymentMethodRepository->getByUserId($_SESSION['user_id']);

renderHeader();
?>

<style>
  :root {
    --white: #FFFFFF;
    --dark: #1A1A1A;
    --divider: #E5E5E5;
    --teal-600: #00B8D4;
    --teal-light: #DDFAFF;
    --font-poppins: 'Poppins', sans-serif;
    --font-montserrat: 'Montserrat', sans-serif;
  }

  .payment-methods-page {
    max-width: 800px;
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

.alert {
  padding: 12px 14px;
  border-radius: 10px;
  margin: 12px 40px 0 40px;
  font-weight: 600;
}

.alert.error {
  background: rgba(220, 38, 38, 0.1);
  color: #b91c1c;
  border: 1px solid rgba(220, 38, 38, 0.3);
}

.alert.success {
  background: rgba(22, 163, 74, 0.1);
  color: #15803d;
  border: 1px solid rgba(22, 163, 74, 0.3);
}

  .pm-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .pm-row {
    background: var(--white);
    border: 1px solid var(--divider);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .pm-row:hover {
    border-color: var(--teal-600);
    box-shadow: 0 4px 12px rgba(0, 184, 212, 0.1);
  }

  .pm-row.pm-add {
    background: var(--teal-light);
    border-color: var(--teal-600);
  }

  .pm-row.pm-add:hover {
    background: #C8F2F8;
    transform: translateY(-2px);
  }

  .pm-row-left {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .pm-add-icon {
    font-size: 24px;
    color: var(--teal-600);
    font-weight: 300;
  }

  .pm-add-text {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
  }

  .card-logo {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark);
  }

  .card-logo-visa {
    color: #1A1F71;
  }

  .card-number {
    font-size: 16px;
    color: var(--dark);
    font-weight: 500;
  }

  .pm-arrow {
    color: var(--dark);
    opacity: 0.5;
  }

.pm-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.delete-btn {
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fecdd3;
  border-radius: 8px;
  padding: 8px 10px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
}

.delete-btn:hover {
  background: #fecdd3;
}

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }
    .title {
      font-size: 20px;
    }
  }
</style>

<div class="payment-methods-page">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/checkout" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Payment Methods</div>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <main class="pm-main" role="main">
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <section class="pm-list" aria-label="Payment methods">
      <!-- Add another card -->
      <div class="pm-row pm-add" onclick="window.location.href='<?= BASE_URL ?>/account/addanothercard'">
        <div class="pm-row-left">
          <span class="pm-add-icon">＋</span>
          <span class="pm-add-text">Add another Card</span>
        </div>
        <span class="pm-arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
      </div>

      <?php if (!empty($paymentMethods)): ?>
        <?php foreach ($paymentMethods as $pm): ?>
          <div class="pm-row" onclick="window.location.href='<?= BASE_URL ?>/account/viewcard?id=<?= (int)$pm['payment_method_id'] ?>'">
            <div class="pm-row-left">
              <span class="card-logo card-logo-visa"><?= htmlspecialchars($pm['card_type']) ?></span>
              <span class="card-number">•••• <?= htmlspecialchars($pm['last_four_digits']) ?></span>
              <?php if ($pm['is_default']): ?>
                <span style="font-size: 12px; color: var(--teal-600); font-weight: 600;">(Default)</span>
              <?php endif; ?>
            </div>
            <div class="pm-actions" onclick="event.stopPropagation();">
              <span class="pm-arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
              <form method="POST" action="<?= BASE_URL ?>/paymentmethod" onsubmit="return confirm('Remove this card?');">
                <input type="hidden" name="delete_payment_id" value="<?= (int)$pm['payment_method_id'] ?>">
                <button type="submit" class="delete-btn" aria-label="Remove card">Remove</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align: center; padding: 40px; color: var(--muted);">
          <p>No payment methods saved. Add a card to get started.</p>
        </div>
      <?php endif; ?>
    </section>
  </main>
</div>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$addressId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($addressId <= 0) {
    $_SESSION['error'] = 'Invalid address ID';
    header('Location: ' . BASE_URL . '/delivery');
    exit;
}

$addressRepository = new AddressRepository();
$address = $addressRepository->getById($addressId, $_SESSION['user_id']);

if (!$address) {
    $_SESSION['error'] = 'Address not found';
    header('Location: ' . BASE_URL . '/delivery');
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

  .view-address-page {
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

  .address-details-card {
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

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }
    .title {
      font-size: 20px;
    }
    .address-details-card {
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

<div class="view-address-page">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="container">
      <div class="top-left">
        <a class="back" href="<?= BASE_URL ?>/account/delivery" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title">Address Details</div>
      </div>
    </div>
  </div>

  <!-- ADDRESS DETAILS -->
  <div class="address-details-card">
    <div class="detail-row">
      <div class="detail-label">Address Type:</div>
      <div class="detail-value">
        <?= htmlspecialchars($address['address_type'] ?? 'Home') ?>
        <?php if ($address['is_default']): ?>
          <span class="default-badge">Default</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Area:</div>
      <div class="detail-value"><?= htmlspecialchars($address['area']) ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Building Name:</div>
      <div class="detail-value"><?= htmlspecialchars($address['building_name'] ?? '') ?></div>
    </div>

    <div class="detail-row">
      <div class="detail-label">Apartment Number:</div>
      <div class="detail-value"><?= htmlspecialchars($address['apartment_number'] ?? '') ?></div>
    </div>

    <?php if (!empty($address['floor'])): ?>
    <div class="detail-row">
      <div class="detail-label">Floor:</div>
      <div class="detail-value"><?= htmlspecialchars($address['floor']) ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($address['street'])): ?>
    <div class="detail-row">
      <div class="detail-label">Street:</div>
      <div class="detail-value"><?= htmlspecialchars($address['street']) ?></div>
    </div>
    <?php endif; ?>

    <div class="detail-row">
      <div class="detail-label">Phone Number:</div>
      <div class="detail-value"><?= htmlspecialchars($address['phone'] ?? '') ?></div>
    </div>

    <?php if (!empty($address['address_label'])): ?>
    <div class="detail-row">
      <div class="detail-label">Address Label:</div>
      <div class="detail-value"><?= htmlspecialchars($address['address_label']) ?></div>
    </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>


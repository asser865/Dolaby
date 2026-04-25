<?php
require_once __DIR__ . '/../src/includes.php';
require_once __DIR__ . '/../src/header_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
$addressRepository = new AddressRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_address_id'])) {
    $addressId = (int)$_POST['delete_address_id'];
    $addressRepository->deleteById($addressId, $_SESSION['user_id']);
    $_SESSION['success'] = 'Address removed successfully';
    header('Location: ' . BASE_URL . '/delivery');
    exit;
}
$addresses = $addressRepository->getByUserId($_SESSION['user_id']);

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

  /* ======================================================= */
  /* -------------------- TOPBAR ----------------------- */
  /* ======================================================= */
  .topbar {
    width: 100%;
    padding: 18px 0;
    border-bottom: 1px solid var(--divider);
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
    background: var(--panel);
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

  /* ======================================================= */
  /* -------------------- MAIN CONTENT -------------------- */
  /* ======================================================= */
  main {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 40px;
  }

  /* Add New Address Button */
  .add-address-btn {
    width: 100%;
    height: 60px;
    background: var(--teal-light);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0 20px;
    gap: 12px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    border: 2px solid transparent;
    font-size: 16px;
    font-weight: 500;
    color: var(--dark);
    text-decoration: none;
  }

  .add-address-btn:hover {
    background-color: #C8F2F8;
  }

  .add-address-btn i {
    font-size: 24px;
    color: var(--dark);
  }

  /* Address Card */
  .address-card {
    background: var(--teal-light);
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 16px;
    display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .address-card:hover {
    background-color: #C8F2F8;
  }

  .address-content {
    flex: 1;
  }

  .address-type {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 6px;
  }

  .address-text {
    font-size: 14px;
    color: #555555;
    font-weight: 500;
  }

  .address-arrow {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 8px;
    font-size: 18px;
    color: var(--dark);
    transition: all 0.2s ease;
    flex-shrink: 0;
    margin-left: 12px;
  }

  .address-card:hover .address-arrow {
    background: rgba(255, 255, 255, 0.9);
    transform: translateX(4px);
  }

.address-actions {
  display: flex;
  align-items: center;
  gap: 10px;
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

  /* ======================================================= */
  /* -------------------- RESPONSIVE -------------------- */
  /* ======================================================= */
  @media (max-width: 1024px) {
    main {
      padding: 40px 80px;
    }
  }

  @media (max-width: 768px) {
    main {
      padding: 40px 40px;
    }
  }

  @media (max-width: 640px) {
    .topbar .container {
      padding: 0 20px;
    }

    .title {
      font-size: 20px;
    }

    main {
      padding: 20px 16px;
    }

    .add-address-btn {
      height: 56px;
      font-size: 15px;
    }

    .address-card {
      padding: 16px 18px;
    }

    .address-type {
      font-size: 15px;
    }

    .address-text {
      font-size: 13px;
    }
  }
</style>

<!-- ======================================================= -->
<!-- -------------------- TOPBAR ----------------------- -->
<!-- ======================================================= -->
<div class="topbar">
  <div class="container">
    <div class="top-left">
      <a class="back" href="<?= BASE_URL ?>/menu" aria-label="Back">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M15 18L9 12l6-6" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      <div class="title">Delivery Addresses</div>
    </div>
  </div>
</div>

<!-- ======================================================= -->
<!-- -------------------- MAIN CONTENT -------------------- -->
<!-- ======================================================= -->
<main>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
  
<a href="<?= BASE_URL ?>/account/addnewadd" class="add-address-btn">
  <i class="fas fa-plus"></i>
  <span>Add new address</span>
</a>

  <?php if (!empty($addresses)): ?>
    <?php foreach ($addresses as $addr): ?>
      <div class="address-card" onclick="window.location.href='<?= BASE_URL ?>/account/viewaddress?id=<?= (int)$addr['address_id'] ?>'">
        <div class="address-content">
          <div class="address-type"><?= htmlspecialchars($addr['address_type'] ?? 'Home') ?></div>
          <div class="address-text">
            <?= htmlspecialchars($addr['area']) ?>
            <?php if (!empty($addr['building_name'])): ?>
              , <?= htmlspecialchars($addr['building_name']) ?>
            <?php endif; ?>
            <?php if (!empty($addr['street'])): ?>
              , <?= htmlspecialchars($addr['street']) ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="address-actions" onclick="event.stopPropagation();">
          <div class="address-arrow">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/delivery" onsubmit="return confirm('Remove this address?');">
            <input type="hidden" name="delete_address_id" value="<?= (int)$addr['address_id'] ?>">
            <button type="submit" class="delete-btn" aria-label="Remove address">Remove</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div style="text-align: center; padding: 40px; color: var(--muted);">
      <p>No addresses saved. Add your first address to get started.</p>
    </div>
  <?php endif; ?>

</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

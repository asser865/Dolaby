<?php
/**
 * Header rendering function
 */
function renderHeader($isBrandOwner = false) {
    // Check if user is brand owner from session
    if (!$isBrandOwner && isset($_SESSION['role']) && $_SESSION['role'] === 'brand_owner') {
        $isBrandOwner = true;
    }
    
    if ($isBrandOwner) {
        // Brand owner header - only logo
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dolaby - Brand Owner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
  <script src="<?= BASE_URL ?>/assets/script.js" defer></script>
</head>
<body>
  <!-- BRAND OWNER HEADER - LOGO AND LOGOUT -->
  <header class="app-header" style="justify-content: space-between; padding: 0 20px;">
    <div class="header-logo"><a href="<?= BASE_URL ?>/brandowner" style="text-decoration: none; color: inherit;">Dolaby</a></div>
    <div class="header-icons" style="display: flex; align-items: center; gap: 12px;">
      <a href="<?= BASE_URL ?>/logout" class="header-icon-btn" title="Logout" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; transition: all 0.3s ease;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>
  </header>
<?php
    } else {
        // Customer header - full navigation
        $cartCount = 0;
        if (isset($_SESSION['user_id'])) {
            $cartService = new CartService();
            $cart = $cartService->getCart($_SESSION['user_id']);
            $cartCount = array_sum(array_column($cart['items'], 'quantity'));
        }
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dolaby</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
  <script src="<?= BASE_URL ?>/assets/script.js" defer></script>
</head>
<body>
  <!-- HEADER -->
  <header class="app-header">
    <div class="header-logo"><a href="<?= BASE_URL ?>" style="text-decoration: none; color: inherit;">Dolaby</a></div>
    <nav class="header-nav align-nav">
      <a href="<?= BASE_URL ?>">Home</a>
      <a href="<?= BASE_URL ?>/shop">Shop</a>
      <a href="<?= BASE_URL ?>/aboutus">About</a>
    </nav>
    <div class="header-icons">
      <svg class="header-icon-btn" width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.4" />
        <circle cx="11" cy="11" r="6.2" stroke="currentColor" stroke-width="1.4" />
      </svg>
      <svg class="header-icon-btn" width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M12 20s-6-4.35-8.2-7.2C1.7 9.9 4 6 8 6c1.9 0 3 1.3 4 2.4 1-1.1 2.1-2.4 4-2.4 4 0 6.3 3.9 4.2 6.8C18 15.6 12 20 12 20z" stroke="currentColor" stroke-width="1.2" />
      </svg>
      <a href="<?= BASE_URL ?>/cart" class="cart-icon header-icon-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3 6h18M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="1.2" />
        </svg>
        <?php if ($cartCount > 0): ?>
          <div class="cart-badge"><?= $cartCount ?></div>
        <?php endif; ?>
      </a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>/account" class="header-icon-btn" title="Account">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.2" />
            <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" stroke="currentColor" stroke-width="1.2" />
          </svg>
        </a>
        <a href="<?= BASE_URL ?>/logout" class="header-icon-btn" title="Logout">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>/login" class="header-icon-btn" title="Login">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.2" />
            <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" stroke="currentColor" stroke-width="1.2" />
          </svg>
        </a>
      <?php endif; ?>
      <div class="background">
        <a href="<?= BASE_URL ?>/menu">
          <button class="menu__icon">
            <span></span>
            <span></span>
            <span></span>
          </button>
        </a>
      </div>
    </div>
  </header>
<?php
    }
}


<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

renderHeader();
?>


<style>
  .menu-page.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
  }

  .menu-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 30px;
  }

  .menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
  }

  .menu-tile {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: var(--white);
    border-radius: var(--card-radius);
    text-decoration: none;
    color: var(--dark);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--divider);
  }

  .menu-tile:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 184, 212, 0.2);
    border-color: var(--teal-600);
  }

  .tile-icon {
    font-size: 32px;
    flex-shrink: 0;
  }

  .tile-text {
    flex: 1;
  }

  .tile-text strong {
    display: block;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--dark);
  }

  .tile-sub {
    font-size: 14px;
    color: var(--muted);
  }
</style>

<main class="menu-page container" role="main">
  <h2 class="menu-title">Account & Settings</h2>

  <nav class="menu-grid" role="navigation" aria-label="Account menu">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a class="menu-tile" href="<?= BASE_URL ?>/myorders">
        <span class="tile-icon">📦</span>
        <div class="tile-text">
          <strong>My Orders</strong>
          <div class="tile-sub">View recent orders</div>
        </div>
      </a>

      <a class="menu-tile" href="<?= BASE_URL ?>/account">
        <span class="tile-icon">👤</span>
        <div class="tile-text">
          <strong>My Profile</strong>
          <div class="tile-sub">Edit personal info</div>
        </div>
      </a>

      <a class="menu-tile" href="<?= BASE_URL ?>/account/delivery">
        <span class="tile-icon">📍</span>
        <div class="tile-text">
          <strong>Delivery Addresses</strong>
          <div class="tile-sub">Manage shipping addresses</div>
        </div>
      </a>

      <a class="menu-tile" href="<?= BASE_URL ?>/account/paymentmethod">
        <span class="tile-icon">💳</span>
        <div class="tile-text">
          <strong>Payment Methods</strong>
          <div class="tile-sub">Manage cards & wallets</div>
        </div>
      </a>
    <?php endif; ?>

    <a class="menu-tile" href="<?= BASE_URL ?>/contactus">
      <span class="tile-icon">✉️</span>
      <div class="tile-text">
        <strong>Contact Us</strong>
        <div class="tile-sub">Support & feedback</div>
      </div>
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>
      <a class="menu-tile" href="<?= BASE_URL ?>/account/settings">
        <span class="tile-icon">⚙️</span>
        <div class="tile-text">
          <strong>Settings</strong>
          <div class="tile-sub">Preferences & privacy</div>
        </div>
      </a>
    <?php endif; ?>

    <a class="menu-tile" href="<?= BASE_URL ?>/helpfaqs">
      <span class="tile-icon">❓</span>
      <div class="tile-text">
        <strong>Help and FAQs</strong>
        <div class="tile-sub">Common questions</div>
      </div>
    </a>

    <a class="menu-tile" href="<?= BASE_URL ?>/aboutus">
      <span class="tile-icon">ℹ️</span>
      <div class="tile-text">
        <strong>About us</strong>
        <div class="tile-sub">Company info</div>
      </div>
    </a>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a class="menu-tile" href="<?= BASE_URL ?>/login">
        <span class="tile-icon">🔐</span>
        <div class="tile-text">
          <strong>Login</strong>
          <div class="tile-sub">Sign in to your account</div>
        </div>
      </a>

      <a class="menu-tile" href="<?= BASE_URL ?>/register">
        <span class="tile-icon">📝</span>
        <div class="tile-text">
          <strong>Register</strong>
          <div class="tile-sub">Create new account</div>
        </div>
      </a>
    <?php else: ?>
      <a class="menu-tile" href="<?= BASE_URL ?>/logout">
        <span class="tile-icon">🚪</span>
        <div class="tile-text">
          <strong>Logout</strong>
          <div class="tile-sub">Sign out of your account</div>
        </div>
      </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'brand_owner'): ?>
      <a class="menu-tile" href="<?= BASE_URL ?>/brandowner/login">
        <span class="tile-icon">🏢</span>
        <div class="tile-text">
          <strong>Brand Owner</strong>
          <div class="tile-sub">Manage your brand</div>
        </div>
      </a>
    <?php endif; ?>
  </nav>
</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

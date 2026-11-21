<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

renderHeader();
?>

<style>
    :root {
      --white: #FFFFFF;
      --dark: #1A1A1A;
      --divider: #E5E5E5;
      --teal-600: #00B8D4;
      --teal-light: #6ED7D9;
      --red: #E53935;
      --font-poppins: 'Poppins', sans-serif;
      --font-montserrat: 'Montserrat', sans-serif;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-poppins);
      background: var(--white);
      color: var(--dark);
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    button {
      border: none;
      background: none;
      padding: 0;
      cursor: pointer;
      font-family: inherit;
    }

    /* ======================================================= */
    /* -------------------- HEADER BAR ---------------------- */
    /* ======================================================= */
    .app-header {
      height: 80px;
      background: var(--white);
      border-bottom: 1px solid var(--divider);
      display: flex;
      align-items: center;
      padding: 0 40px;
    }

    .header-logo {
      font-family: var(--font-montserrat);
      font-weight: 800;
      font-size: 26px;
      color: var(--dark);
      margin-right: 40px;
      white-space: nowrap;
    }

    .header-nav {
      display: flex;
      gap: 30px;
      align-items: center;
      flex: 0 0 auto;
    }

    .header-nav a {
      font-size: 16px;
      font-weight: 500;
      color: var(--dark);
      transition: color 0.2s;
    }

    .header-nav a:hover,
    .header-nav a.active {
      color: var(--teal-600);
    }

    .header-icons {
      display: flex;
      align-items: center;
      gap: 24px;
      margin-left: auto;
    }

    .header-icon-btn {
      width: 24px;
      height: 24px;
      color: var(--dark);
      cursor: pointer;
    }

    .header-icon-btn svg {
      stroke: var(--dark);
    }

    /* Cart Icon */
    .cart-icon {
      color: var(--teal-600);
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cart-icon svg {
      stroke: var(--teal-600) !important;
      width: 26px;
      height: 26px;
      transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .cart-icon:hover svg {
      transform: scale(1.1);
      opacity: 0.8;
    }

    .cart-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--teal-600);
      color: var(--white);
      border-radius: 50%;
      font-size: 10px;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
    }

    /* Menu Background */
    .background {
      backdrop-filter: blur(15px);
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Menu Icon */
    .menu__icon {
      width: 32px;
      height: 32px;
      padding: 4px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: end;
      transition: transform .4s;
    }

    .menu__icon span {
      width: 100%;
      height: 0.25rem;
      border-radius: 0.125rem;
      background-color: var(--teal-600);
      box-shadow: 0 .5px 2px 0 var(--teal-light);
      transition: width .3s, transform .3s, background-color .3s;
    }

    .menu__icon span:nth-child(2) {
      width: 75%;
    }

    .menu__icon span:nth-child(3) {
      width: 50%;
    }

    .menu__icon:hover {
      transform: rotate(-90deg);
    }

    .menu__icon:hover span {
      width: .25rem;
      transform: translateX(-10px);
      background-color: rgb(0, 0, 0);
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
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .title svg {
      width: 24px;
      height: 24px;
    }

    /* ======================================================= */
    /* -------------------- MAIN CONTENT -------------------- */
    /* ======================================================= */
    main {
      max-width: 800px;
      margin: 0 auto;
      padding: 40px 40px;
    }

    /* Settings Menu Item */
    .settings-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0;
      border-bottom: 1px solid var(--divider);
      cursor: pointer;
      transition: opacity 0.2s ease;
    }

    .settings-item:last-child {
      border-bottom: none;
    }

    .settings-item:hover {
      opacity: 0.7;
    }

    .settings-label {
      font-size: 18px;
      font-weight: 600;
      color: var(--dark);
    }

    .settings-item.delete .settings-label {
      color: var(--red);
    }

    .settings-arrow {
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--dark);
      transition: transform 0.2s ease;
    }

    .settings-item.delete .settings-arrow {
      color: var(--red);
    }

    .settings-item:hover .settings-arrow {
      transform: translateX(4px);
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
      .app-header {
        padding: 0 20px;
      }

      .header-nav {
        display: none;
      }

      .topbar .container {
        padding: 0 20px;
      }

      .title {
        font-size: 20px;
      }

      main {
        padding: 20px 16px;
      }

      .settings-label {
        font-size: 16px;
      }

      .settings-item {
        padding: 18px 0;
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
        <div class="title">Settings</div>
      </div>
    </div>
  </div>

  <!-- ======================================================= -->
  <!-- -------------------- MAIN CONTENT -------------------- -->
  <!-- ======================================================= -->
  <main>
    
    <!-- Notifications & SMS -->
    <a href="#" class="settings-item">
      <span class="settings-label">Notifications & SMS</span>
      <div class="settings-arrow">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </a>

    <!-- Accessibility -->
    <a href="#" class="settings-item">
      <span class="settings-label">Accessibility</span>
      <div class="settings-arrow">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </a>

    <!-- Language -->
    <a href="#" class="settings-item">
      <span class="settings-label">Language</span>
      <div class="settings-arrow">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </a>

    <!-- Delete Account -->
    <a href="<?= BASE_URL ?>/account/deleteacc" class="settings-item delete">
      <span class="settings-label">Delete Account</span>
      <div class="settings-arrow">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </a>

  </main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to brand owner login page if not logged in
    header('Location: ' . BASE_URL . '/brandowner/login');
    exit;
} else {
    if ($_SESSION['role'] !== 'brand_owner') {
        $_SESSION['error'] = 'Access denied. Brand owner access required.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

renderHeader(true);
?>

<style>
  body {
    background: var(--bg);
    min-height: 100vh;
  }

  .main {
    padding: 30px 0 60px;
    animation: fadeIn 0.6s ease-in;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Enhanced Topbar */
  .topbar {
    margin-bottom: 40px;
    padding: 20px 0;
  }

  .title {
    font-size: 42px;
    font-weight: 900;
    background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    padding-bottom: 12px;
  }

  .title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    border-radius: 2px;
  }

  .back-btn {
    transition: all 0.3s ease;
    border-radius: 10px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .back-btn:hover {
    background: rgba(0, 184, 212, 0.1);
    transform: translateX(-2px);
  }

  /* Enhanced Cards Container */
  .cards {
    gap: 24px;
    margin-top: 32px;
  }

  /* Enhanced Card Styling */
  .cards .card {
    background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    border: 1px solid rgba(0, 184, 212, 0.15);
    box-shadow: 0 4px 20px rgba(0, 184, 212, 0.08);
    padding: 32px 36px;
    border-radius: 16px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 120px;
    animation: slideUp 0.6s ease-out;
    animation-fill-mode: both;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .cards .card:nth-child(1) { animation-delay: 0.1s; }
  .cards .card:nth-child(2) { animation-delay: 0.2s; }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .cards .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
  }

  .cards .card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(0, 184, 212, 0.05) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .cards .card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 40px rgba(0, 184, 212, 0.2);
    border-color: rgba(0, 184, 212, 0.3);
  }

  .cards .card:hover::before {
    transform: scaleX(1);
  }

  .cards .card:hover::after {
    opacity: 1;
  }

  /* Card Body Enhancements */
  .card-body {
    position: relative;
    z-index: 1;
    flex: 1;
  }

  .label {
    font-size: 28px;
    font-weight: 900;
    color: var(--dark);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: color 0.3s ease;
    line-height: 1.3;
  }

  .cards .card:hover .label {
    background: linear-gradient(135deg, var(--dark) 0%, var(--teal-600) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .meta {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
    font-weight: 500;
    margin-top: 4px;
    transition: color 0.3s ease;
  }

  .cards .card:hover .meta {
    color: var(--dark);
  }

  /* Enhanced Chevron */
  .chev {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(0, 184, 212, 0.1) 0%, rgba(0, 184, 212, 0.05) 100%);
    color: var(--teal-600);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 1;
    flex-shrink: 0;
  }

  .chev svg {
    transition: transform 0.4s ease;
  }

  .cards .card:hover .chev {
    background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
    color: white;
    transform: translateX(4px);
    box-shadow: 0 4px 16px rgba(0, 184, 212, 0.3);
  }

  .cards .card:hover .chev svg {
    transform: translateX(4px);
  }

  /* Icon Background Enhancement */
  .label i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    font-size: 24px;
    color: var(--teal-600);
    background: linear-gradient(135deg, rgba(0, 184, 212, 0.1) 0%, rgba(0, 184, 212, 0.05) 100%);
    border-radius: 14px;
    margin-right: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
  }

  .cards .card:hover .label i {
    background: linear-gradient(135deg, rgba(0, 184, 212, 0.2) 0%, rgba(0, 184, 212, 0.15) 100%);
    transform: scale(1.1) rotate(-5deg);
    color: var(--teal-500);
    box-shadow: 0 4px 16px rgba(0, 184, 212, 0.2);
  }

  /* Responsive Enhancements */
  @media (max-width: 768px) {
    .title {
      font-size: 28px;
    }

    .cards .card {
      padding: 24px 28px;
      min-height: 100px;
    }

    .label {
      font-size: 24px;
    }

    .label i {
      font-size: 24px;
    }

    .chev {
      width: 40px;
      height: 40px;
    }
  }
</style>

<!-- Main content -->
<main class="main" role="main" aria-labelledby="main-heading">
  <div class="container">
    <div class="topbar">
      <div class="left">
        <a href="<?= BASE_URL ?>/auth/logout" class="back-btn" aria-label="Back to previous">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="1.6" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </a>
        <div class="title" id="page-title">Brand Owner</div>
      </div>

      <div class="right">
      </div>
    </div>

    <div class="cards" role="list">
      <a class="card" href="<?= BASE_URL ?>/brandowner/discount" role="listitem" aria-label="Discount campaigns and promotions">
        <div class="card-body">
          <div class="label">
            <i class="fas fa-bullhorn"></i>
            Discount campaigns &amp; promotions
          </div>
          <div class="meta">Create and manage promotional campaigns</div>
        </div>
        <div class="chev" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" />
          </svg></div>
      </a>

      <a class="card" href="<?= BASE_URL ?>/brandowner/analysis" role="listitem" aria-label="Brand analytics">
        <div class="card-body">
          <div class="label">
            <i class="fas fa-chart-line"></i>
            Brand analytics
          </div>
          <div class="meta">View performance, reports and insights</div>
        </div>
        <div class="chev" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" />
          </svg></div>
      </a>
    </div>
  </div>
</main>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

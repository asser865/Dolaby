<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'brand_owner') {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

renderHeader(true);
?>

<style>
    /* ======================================================= */
    /* -------------------- ENHANCED DISCOUNT PAGE STYLES --- */
    /* ======================================================= */
    
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
      margin-bottom: 32px;
      padding: 20px 0;
    }

    .title {
      font-size: 42px;
      font-weight: 900;
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .back-btn {
      transition: all 0.3s ease;
      border-radius: 10px;
      padding: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .back-btn:hover {
      background: rgba(0, 184, 212, 0.1);
      transform: translateX(-2px);
    }

    /* Enhanced Summary Card */
    .summary-card {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 8px 32px rgba(0, 184, 212, 0.1);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      padding: 20px 24px;
    }

    .summary-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .summary-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 184, 212, 0.15);
      border-color: rgba(0, 184, 212, 0.3);
    }

    .summary-badge {
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      color: white;
      font-weight: 800;
      font-size: 20px;
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.3);
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .summary-count {
      font-weight: 700;
      font-size: 16px;
      color: var(--dark);
      margin-bottom: 4px;
    }

    .summary-label {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.4;
    }

    /* Enhanced Primary Action Button */
    .primary-action {
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      box-shadow: 0 4px 20px rgba(0, 184, 212, 0.3);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .primary-action::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .primary-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 30px rgba(0, 184, 212, 0.4);
    }

    .primary-action:hover::before {
      width: 300px;
      height: 300px;
    }

    .primary-action svg {
      position: relative;
      z-index: 1;
      transition: transform 0.3s ease;
    }

    .primary-action:hover svg {
      transform: rotate(90deg);
    }

    /* Enhanced Tools Section */
    .tools {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.1);
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .search {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 2px solid rgba(0, 184, 212, 0.15);
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .search:focus-within {
      border-color: var(--teal-600);
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.15);
      transform: translateY(-2px);
    }

    .search svg {
      color: var(--teal-600);
      transition: transform 0.3s ease;
    }

    .search:focus-within svg {
      transform: scale(1.1);
    }

    .search input {
      color: var(--dark);
      font-weight: 500;
    }

    .search input::placeholder {
      color: var(--muted);
    }

    .select {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 2px solid rgba(0, 184, 212, 0.15);
      border-radius: 10px;
      transition: all 0.3s ease;
      font-weight: 500;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .select:hover {
      border-color: var(--teal-600);
      box-shadow: 0 4px 12px rgba(0, 184, 212, 0.1);
      transform: translateY(-1px);
    }

    .select:focus {
      outline: none;
      border-color: var(--teal-600);
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.15);
    }

    /* Enhanced Campaign Cards */
    .list {
      gap: 20px;
    }

    .campaign {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      padding: 20px;
      animation: slideIn 0.5s ease-out;
      animation-fill-mode: both;
    }

    .campaign:nth-child(1) { animation-delay: 0.1s; }
    .campaign:nth-child(2) { animation-delay: 0.2s; }
    .campaign:nth-child(3) { animation-delay: 0.3s; }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .campaign::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.3s ease;
    }

    .campaign:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 184, 212, 0.15);
      border-color: rgba(0, 184, 212, 0.3);
    }

    .campaign:hover::before {
      transform: scaleX(1);
    }

    .campaign[data-status="active"]::before {
      background: linear-gradient(90deg, #16A34A 0%, #22C55E 100%);
    }

    .campaign[data-status="scheduled"]::before {
      background: linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%);
    }

    .campaign[data-status="ended"]::before {
      background: linear-gradient(90deg, #6B7280 0%, #9CA3AF 100%);
    }

    .thumb {
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      border: 2px solid rgba(0, 184, 212, 0.1);
    }

    .campaign:hover .thumb {
      transform: scale(1.05);
      box-shadow: 0 6px 24px rgba(0, 184, 212, 0.2);
    }

    .thumb img {
      transition: transform 0.3s ease;
    }

    .campaign:hover .thumb img {
      transform: scale(1.1);
    }

    .campaign-title {
      font-size: 18px;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      color: var(--dark);
      font-weight: 800;
    }

    .campaign-title i {
      flex-shrink: 0;
    }

    .campaign-sub {
      font-weight: 500;
      color: var(--muted);
      font-size: 14px;
    }

    /* Enhanced Status Badges */
    .status {
      font-weight: 700;
      font-size: 12px;
      padding: 8px 14px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .status.active {
      background: linear-gradient(135deg, #16A34A 0%, #22C55E 100%);
      color: white;
    }

    .status.active::before {
      content: '●';
      font-size: 8px;
      animation: blink 2s ease-in-out infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }

    .status.scheduled {
      background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
      color: white;
    }

    .status.scheduled::before {
      content: '⏰';
      font-size: 10px;
    }

    .status.ended {
      background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
      color: white;
    }

    .status.ended::before {
      content: '✓';
      font-size: 10px;
    }

    .status:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Kebab Button */
    .kebab {
      transition: all 0.3s ease;
      padding: 8px;
      border-radius: 8px;
      background: transparent;
    }

    .kebab:hover {
      background: rgba(0, 184, 212, 0.1);
      transform: rotate(90deg);
    }

    /* Enhanced Modal */
    .modal-backdrop {
      backdrop-filter: blur(8px);
      animation: fadeIn 0.3s ease;
    }

    .modal {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .modal::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .modal-header {
      padding-bottom: 16px;
      border-bottom: 1px solid var(--divider);
      margin-bottom: 20px;
    }

    .modal-title {
      font-size: 24px;
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    #closeModal {
      transition: all 0.3s ease;
      border-radius: 8px;
      padding: 6px;
    }

    #closeModal:hover {
      background: rgba(0, 184, 212, 0.1);
      transform: rotate(90deg);
    }

    /* Enhanced Flow Steps */
    .flow {
      background: rgba(0, 184, 212, 0.05);
      border-radius: 12px;
      padding: 8px;
      margin-bottom: 24px;
    }

    .step {
      background: transparent;
      border: 2px solid transparent;
      transition: all 0.3s ease;
      position: relative;
    }

    .step.active {
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      color: white;
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.3);
      border-color: var(--teal-600);
    }

    .step:not(.active) {
      color: var(--muted);
      border-color: var(--divider);
    }

    .step:not(.active):hover {
      background: rgba(0, 184, 212, 0.05);
      border-color: var(--teal-600);
      color: var(--teal-600);
    }

    /* Enhanced Form Fields */
    .field input,
    .field select {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 2px solid rgba(0, 184, 212, 0.15);
      border-radius: 10px;
      transition: all 0.3s ease;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .field input:focus,
    .field select:focus {
      outline: none;
      border-color: var(--teal-600);
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.15);
      transform: translateY(-1px);
    }

    .field label {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 8px;
    }

    /* Enhanced Buttons */
    .btn-primary {
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      box-shadow: 0 4px 16px rgba(0, 184, 212, 0.3);
      transition: all 0.3s ease;
      font-weight: 700;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(0, 184, 212, 0.4);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 2px solid rgba(0, 184, 212, 0.15);
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-secondary:hover {
      border-color: var(--teal-600);
      background: rgba(0, 184, 212, 0.05);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 184, 212, 0.1);
    }

    /* Responsive Enhancements */
    @media (max-width: 768px) {
      .title {
        font-size: 36px;
      }

      .tools {
        flex-direction: column;
      }

      .filters {
        flex-direction: column;
        width: 100%;
      }

      .select {
        width: 100%;
      }

      .campaign {
        flex-direction: column;
        text-align: center;
      }

      .thumb {
        width: 100%;
        height: 200px;
      }
    }
  </style>

  <!-- MAIN content -->
  <main class="main" role="main" aria-labelledby="page-title">
    <div class="container">
      <div class="topbar">
        <div class="left">
          <a href="<?= BASE_URL ?>/brandowner" class="back-btn" aria-label="Back to previous">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="1.6" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
          </a>
          <div class="title" id="page-title">Discount Campaigns & Promotions</div>
        </div>

        <div class="right">
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
            <div style="display:flex;align-items:center;gap:10px">

            </div>
          </div>
        </div>
      </div>

      <!-- summary + action -->
      <div class="summary-row" style="align-items:center">
        <div class="summary-left" style="align-items:center">
          <div>
            <div style="font-size:14px;color:var(--muted);font-weight:600;margin-bottom:6px;display:flex;align-items:center;gap:8px">
              <i class="fas fa-bullhorn" style="color: var(--teal-600);"></i>
              Campaigns
            </div>

            <!-- UPDATED summary card -->
            <div style="display:flex;align-items:center;gap:10px">
              <div class="summary-card">
                <div class="summary-badge" aria-hidden="true">3</div>

                <div class="summary-body">
                  <div class="summary-count">Active campaigns</div>
                  <div class="summary-label">Manage and track your live promotions</div>
                </div>

                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="opacity:.9">
                  <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px">
          <button class="primary-action" id="createBtn" title="Create new campaign">
            <!-- plus icon -->
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="margin-left:2px">
              <path d="M12 5v14M5 12h14" stroke="white" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
            Create New Campaign
          </button>
        </div>
      </div>

      <!-- tools -->
      <div class="tools">
        <div class="search" role="search" aria-label="Search campaigns">
          <i class="fas fa-search" style="color: var(--teal-600); font-size: 16px;"></i>
          <input id="q" type="search" placeholder="Search campaigns, products..." aria-label="Search campaigns" />
        </div>

        <div class="filters">
          <select id="statusFilter" class="select">
            <option value="">Status — All</option>
            <option value="active">Active</option>
            <option value="scheduled">Scheduled</option>
            <option value="ended">Ended</option>
          </select>

          <select id="groupFilter" class="select">
            <option value="">Product Group — All</option>
            <option value="men">Men</option>
            <option value="women">Women</option>
            <option value="kids">Kids</option>
          </select>

          <select id="sortBy" class="select">
            <option value="recent">Sort: Most recent</option>
            <option value="ending">Sort: Ending soon</option>
            <option value="status">Sort: Status</option>
          </select>
        </div>
      </div>

      <!-- campaign list -->
      <section class="list" aria-live="polite">
        <!-- Campaign 1 -->
        <article class="campaign" data-status="active">
          <div class="thumb" aria-hidden="true">
            <!-- sample thumbnail using uploaded screenshot path -->
            <img src="<?= BASE_URL ?>/assets/images/images/Summer Kickoff Sale.png" alt="Summer Kickoff Sale">
          </div>

          <div class="campaign-main">
            <div class="campaign-title">
              <i class="fas fa-fire" style="color: var(--teal-600); margin-right: 8px; font-size: 14px;"></i>
              Summer Kickoff Sale
            </div>
            <div class="campaign-sub">
              <i class="fas fa-tag" style="margin-right: 6px;"></i>
              20% OFF — Ends in 5 days
            </div>
            <div style="margin-top:8px"><span class="status active">Active</span></div>
          </div>

          <div class="meta-right" aria-hidden="false">
            <button class="kebab" title="More actions" aria-label="More actions">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="5" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="12" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="19" cy="12" r="1.6" fill="#6B7280" />
              </svg>
            </button>
          </div>
        </article>

        <!-- Campaign 2 -->
        <article class="campaign" data-status="scheduled">
          <div class="thumb" aria-hidden="true">
            <img src="<?= BASE_URL ?>/assets/images/images/Autumn Collection Launch.png" alt="Autumn Collection Launch">
          </div>

          <div class="campaign-main">
            <div class="campaign-title">
              <i class="fas fa-leaf" style="color: var(--teal-600); margin-right: 8px; font-size: 14px;"></i>
              Autumn Collection Launch
            </div>
            <div class="campaign-sub">
              <i class="fas fa-tag" style="margin-right: 6px;"></i>
              15% OFF — Starts Oct 1st
            </div>
            <div style="margin-top:8px"><span class="status scheduled">Scheduled</span></div>
          </div>

          <div class="meta-right">
            <button class="kebab" title="More actions" aria-label="More actions">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="5" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="12" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="19" cy="12" r="1.6" fill="#6B7280" />
              </svg>
            </button>
          </div>
        </article>

        <!-- Campaign 3 -->
        <article class="campaign" data-status="ended">
          <div class="thumb" aria-hidden="true">
            <img src="<?= BASE_URL ?>/assets/images/images/End of Season Clearance.png" alt="End of Season Clearance">
          </div>

          <div class="campaign-main">
            <div class="campaign-title">
              <i class="fas fa-shopping-bag" style="color: var(--teal-600); margin-right: 8px; font-size: 14px;"></i>
              End of Season Clearance
            </div>
            <div class="campaign-sub">
              <i class="fas fa-tag" style="margin-right: 6px;"></i>
              50% OFF — Ended Aug 31st
            </div>
            <div style="margin-top:8px"><span class="status ended">Ended</span></div>
          </div>

          <div class="meta-right">
            <button class="kebab" title="More actions" aria-label="More actions">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="5" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="12" cy="12" r="1.6" fill="#6B7280" />
                <circle cx="19" cy="12" r="1.6" fill="#6B7280" />
              </svg>
            </button>
          </div>
        </article>
      </section>
    </div>
  </main>

  <!-- CREATE CAMPAIGN MODAL (unchanged) -->
  <div class="modal-backdrop" id="modal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <div class="modal-header">
        <div>
          <div class="modal-title" id="modal-title">Create New Campaign</div>
          <div style="font-size:13px;color:var(--muted);margin-top:6px">Set campaign details, select products and
            publish.</div>
        </div>
        <button id="closeModal" title="Close" aria-label="Close modal"
          style="background:transparent;border:0;cursor:pointer">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M18 6L6 18M6 6l12 12" stroke="#6B7280" stroke-width="1.6" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
      </div>

      <div class="flow" aria-hidden="false">
        <div class="step active">1. Details</div>
        <div class="step">2. Products</div>
        <div class="step">3. Review</div>
      </div>

      <form class="form" id="campaignForm">
        <!-- Details -->
        <div class="row">
          <div class="field" style="flex:2">
            <label for="campaignName">Campaign name</label>
            <input id="campaignName" name="campaignName" type="text" placeholder="Summer Kickoff Sale" required>
          </div>

          <div class="field" style="flex:1">
            <label for="discountType">Discount type</label>
            <select id="discountType" name="discountType">
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed amount</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="field">
            <label for="startDate">Start date</label>
            <input id="startDate" type="date">
          </div>
          <div class="field">
            <label for="endDate">End date</label>
            <input id="endDate" type="date">
          </div>
        </div>

        <div class="row">
          <div class="field" style="flex:1">
            <label for="productGroup">Product group</label>
            <select id="productGroup">
              <option value="">All products</option>
              <option value="men">Men</option>
              <option value="women">Women</option>
              <option value="kids">Kids</option>
            </select>
          </div>

          <div class="field" style="flex:1">
            <label for="discountValue">Discount value</label>
            <input id="discountValue" type="text" placeholder="20%">
          </div>
        </div>

        <!-- Product selection (step 2 placeholder) -->
        <div id="productSelection" style="display:none;">
          <div style="font-weight:700;margin-top:12px">Product Selection</div>
          <div style="margin-top:8px;color:var(--muted);font-size:13px">(Select products or groups to include in this
            campaign.)</div>

          <div style="display:flex;gap:10px;margin-top:12px;">
            <input type="text" placeholder="Search products..."
              style="flex:1;padding:10px;border-radius:8px;border:1px solid var(--divider)">
            <button class="btn-secondary" type="button">Add selected</button>
          </div>
        </div>

        <!-- Review (step 3) -->
        <div id="review" style="display:none;">
          <div style="font-weight:700;margin-top:12px">Review &amp; Publish</div>
          <div style="margin-top:8px;color:var(--muted);font-size:13px">Confirm campaign details before publishing.
          </div>

          <div style="margin-top:12px;border:1px solid var(--divider);padding:12px;border-radius:8px;background:#fff;">
            <div id="reviewOutput" style="font-size:14px;color:var(--dark)"></div>
          </div>
        </div>

        <div class="modal-actions">
          <div style="display:flex;gap:8px">
            <button type="button" class="btn-secondary" id="prevBtn" style="display:none;">Back</button>
          </div>
          <div style="display:flex;gap:8px">
            <button type="button" class="btn-secondary" id="cancelBtn">Cancel</button>
            <button type="button" class="btn-primary" id="nextBtn">Next</button>
            <button type="submit" class="btn-primary" id="publishBtn" style="display:none;">Publish</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Local script -->
  <script src="<?= BASE_URL ?>/assets/script.js"></script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

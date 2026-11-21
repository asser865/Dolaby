<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'brand_owner') {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$promoRepo = new PromoCodeRepository();
$error = null;
$success = isset($_GET['success']);

// Handle form submission to create a promo code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_promo') {
    $promoData = [
        'code' => $_POST['promoCode'] ?? '',
        'type' => $_POST['discountType'] ?? 'percent',
        'value' => $_POST['discountValue'] ?? 0,
        'min_order_amount' => !empty($_POST['minOrder']) ? $_POST['minOrder'] : null,
        'max_discount' => !empty($_POST['maxDiscount']) ? $_POST['maxDiscount'] : null,
        'start_date' => !empty($_POST['startDate']) ? $_POST['startDate'] : null,
        'end_date' => !empty($_POST['endDate']) ? $_POST['endDate'] : null,
        'usage_limit' => !empty($_POST['usageLimit']) ? $_POST['usageLimit'] : null,
    ];
    
    try {
        $promoRepo->create($promoData);
        header('Location: ' . BASE_URL . '/brandowner/discount?success=1');
        exit;
    } catch (Exception $e) {
        $error = "Error creating promo code. Make sure the code is unique.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_promo') {
    $promoId = $_POST['promo_id'] ?? null;
    if ($promoId) {
        try {
            $promoRepo->delete((int)$promoId);
            header('Location: ' . BASE_URL . '/brandowner/discount?deleted=1');
            exit;
        } catch (Exception $e) {
            $error = "Error deleting promo code.";
        }
    }
}

$promoCodes = $promoRepo->findAll();
$activeCount = 0;
$today = date('Y-m-d');
foreach ($promoCodes as $p) {
    if ((!$p['end_date'] || $p['end_date'] >= $today) && (!$p['start_date'] || $p['start_date'] <= $today)) {
        $activeCount++;
    }
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

    .topbar {
      margin-bottom: 32px;
      padding: 20px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      margin-right: 15px;
      text-decoration: none;
    }

    .back-btn:hover {
      background: rgba(0, 184, 212, 0.1);
      transform: translateX(-2px);
    }

    .summary-card {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 8px 32px rgba(0, 184, 212, 0.1);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      border-radius: 16px;
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

    .primary-action {
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      box-shadow: 0 4px 20px rgba(0, 184, 212, 0.3);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .primary-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 30px rgba(0, 184, 212, 0.4);
    }

    .list {
      display: grid;
      gap: 20px;
      margin-top: 24px;
    }

    .campaign {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      padding: 24px;
      border-radius: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .campaign::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      width: 6px;
      background: linear-gradient(180deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .campaign:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 184, 212, 0.15);
      border-color: rgba(0, 184, 212, 0.3);
    }

    .campaign-title {
      font-size: 20px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      color: var(--dark);
      font-weight: 900;
      letter-spacing: 1px;
    }

    .campaign-sub {
      font-weight: 500;
      color: var(--muted);
      font-size: 14px;
      margin-bottom: 12px;
    }

    .status {
      font-weight: 700;
      font-size: 12px;
      padding: 8px 14px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .status.active {
      background: linear-gradient(135deg, #16A34A 0%, #22C55E 100%);
      color: white;
    }

    .status.ended {
      background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
      color: white;
    }

    .status.scheduled {
      background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
      color: white;
    }

    /* Modal Styles */
    .modal-backdrop {
      backdrop-filter: blur(8px);
      background: rgba(0, 0, 0, 0.4);
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.15);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 500px;
      border-radius: 16px;
      padding: 24px;
      position: relative;
    }

    .modal-header {
      padding-bottom: 16px;
      border-bottom: 1px solid var(--divider);
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 24px;
      font-weight: 800;
      color: var(--teal-600);
    }

    .field {
      margin-bottom: 16px;
      display: flex;
      flex-direction: column;
    }

    .row {
      display: flex;
      gap: 16px;
    }

    .field input, .field select {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border: 2px solid rgba(0, 184, 212, 0.15);
      border-radius: 10px;
      padding: 10px;
      font-weight: 500;
      color: var(--dark);
    }

    .field input:focus, .field select:focus {
      outline: none;
      border-color: var(--teal-600);
    }

    .field label {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 8px;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-danger { background: #fee2e2; color: #991b1b; }

</style>

<main class="main" role="main">
  <div class="container">
    
    <div class="topbar">
      <div style="display:flex; align-items:center;">
        <a href="<?= BASE_URL ?>/brandowner" class="back-btn">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <div class="title">Promo Codes</div>
      </div>
      <button class="primary-action" id="createBtn">
        <i class="fas fa-plus"></i> Create Promo Code
      </button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> Promo Code created successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success"><i class="fas fa-trash-alt"></i> Promo Code deleted successfully!</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="summary-card">
      <div class="summary-badge"><?= $activeCount ?></div>
      <div>
        <div class="summary-count">Active Promo Codes</div>
        <div class="summary-label">Manage and track your customer discounts.</div>
      </div>
    </div>

    <section class="list">
      <?php if (empty($promoCodes)): ?>
        <div style="text-align:center; padding: 40px; color: var(--muted); background: white; border-radius: 16px; border: 1px dashed var(--divider);">
            <i class="fas fa-ticket-alt" style="font-size: 48px; color: var(--teal-300); margin-bottom: 16px;"></i>
            <h3>No promo codes found</h3>
            <p>Create your first promo code to start giving discounts.</p>
        </div>
      <?php else: ?>
          <?php foreach ($promoCodes as $promo): ?>
            <?php
                // Determine status
                $status = 'active';
                $statusLabel = 'Active';
                $today = date('Y-m-d');
                if (!$promo['is_active']) {
                    $status = 'ended'; // Using 'ended' class for red/gray styling
                    $statusLabel = 'Inactive (Killed)';
                } elseif ($promo['start_date'] && $promo['start_date'] > $today) {
                    $status = 'scheduled';
                    $statusLabel = 'Scheduled';
                } elseif ($promo['end_date'] && $promo['end_date'] < $today) {
                    $status = 'ended';
                    $statusLabel = 'Expired';
                }

                // Format value
                $valueDisplay = $promo['type'] === 'percent' ? $promo['value'] . '%' : $promo['value'] . ' EGP';
            ?>
            <article class="campaign">
              <div class="campaign-main">
                <div class="campaign-title">
                  <i class="fas fa-ticket-alt" style="color: var(--teal-600); margin-right: 12px;"></i>
                  <?= htmlspecialchars($promo['code']) ?>
                </div>
                <div class="campaign-sub">
                  <i class="fas fa-tag" style="margin-right: 6px;"></i>
                  Discount: <strong><?= $valueDisplay ?> OFF</strong>
                  <?php if ($promo['min_order_amount']): ?>
                     | Min Order: <?= number_format($promo['min_order_amount']) ?> EGP
                  <?php endif; ?>
                </div>
                <div style="font-size: 13px; color: var(--muted); margin-bottom: 12px;">
                    <i class="far fa-calendar-alt" style="margin-right: 6px;"></i>
                    Valid: <?= $promo['start_date'] ?: 'Forever' ?> to <?= $promo['end_date'] ?: 'Forever' ?>
                </div>
                <div><span class="status <?= $status ?>"><?= $statusLabel ?></span></div>
              </div>

              <div style="text-align:right;">
                  <div style="font-size: 24px; font-weight: 800; color: var(--teal-600);">
                      <?= number_format($promoRepo->getUsageCount($promo['promo_code_id'])) ?>
                  </div>
                  <div style="font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 12px;">Times Used</div>
                  
                  <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this promo code?');" style="display: inline-block;">
                      <input type="hidden" name="action" value="delete_promo">
                      <input type="hidden" name="promo_id" value="<?= $promo['promo_code_id'] ?>">
                      <button type="submit" style="background: #fee2e2; color: #991b1b; border: 1px solid #f87171; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                          <i class="fas fa-trash-alt" style="margin-right: 4px;"></i> Delete
                      </button>
                  </form>
              </div>
            </article>
          <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </div>
</main>

<!-- CREATE PROMO MODAL -->
<div class="modal-backdrop" id="modal">
  <div class="modal">
    <div class="modal-header">
      <div>
        <div class="modal-title">Create Promo Code</div>
        <div style="font-size:13px;color:var(--muted);margin-top:6px">Customers enter this code at checkout.</div>
      </div>
      <button id="closeModal" style="background:transparent;border:0;cursor:pointer;font-size:20px;">
        <i class="fas fa-times" style="color:var(--muted);"></i>
      </button>
    </div>

    <form method="POST" action="">
      <input type="hidden" name="action" value="create_promo">
      
      <div class="field">
        <label>Promo Code String</label>
        <input name="promoCode" type="text" placeholder="e.g. SUMMER20" required style="text-transform: uppercase;">
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Discount Type</label>
          <select name="discountType">
            <option value="percent">Percentage (%)</option>
            <option value="fixed">Fixed Amount (EGP)</option>
          </select>
        </div>
        <div class="field" style="flex:1">
          <label>Discount Value</label>
          <input name="discountValue" type="number" step="0.01" placeholder="e.g. 20" required>
        </div>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Minimum Order (Optional)</label>
          <input name="minOrder" type="number" step="0.01" placeholder="0.00">
        </div>
        <div class="field" style="flex:1">
          <label>Usage Limit (Optional)</label>
          <input name="usageLimit" type="number" placeholder="e.g. 100">
        </div>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label>Start Date (Optional)</label>
          <input name="startDate" type="date">
        </div>
        <div class="field" style="flex:1">
          <label>End Date (Optional)</label>
          <input name="endDate" type="date">
        </div>
      </div>

      <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
        <button type="button" class="primary-action" style="background: white; color: var(--dark); border: 1px solid var(--divider);" id="cancelBtn">Cancel</button>
        <button type="submit" class="primary-action">Create Promo Code</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('modal');
  const createBtn = document.getElementById('createBtn');
  const closeModal = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelBtn');

  function openModal() { modal.style.display = 'flex'; }
  function hideModal() { modal.style.display = 'none'; }

  createBtn.addEventListener('click', openModal);
  closeModal.addEventListener('click', hideModal);
  cancelBtn.addEventListener('click', hideModal);
  
  // Close if clicking outside the modal content
  modal.addEventListener('click', (e) => {
    if(e.target === modal) hideModal();
  });
</script>

</body>
</html>

<?php
require_once __DIR__ . '/../includes.php';
require_once __DIR__ . '/../header_functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'brand_owner') {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$analytics = new AnalyticsService();
$totalSales = $analytics->getTotalSales();
$totalCustomers = $analytics->getUniqueCustomers();
$returningRate = $analytics->getReturningCustomerRate();
$demographics = $analytics->getCategoryDemographics();
$topProducts = $analytics->getTopProducts(3);

// Find the dominant demographic for the center of the donut chart
$dominantDemo = 'Women';
$dominantValue = $demographics['Women'];
if ($demographics['Men'] > $dominantValue) {
    $dominantDemo = 'Men';
    $dominantValue = $demographics['Men'];
}
if ($demographics['Kids'] > $dominantValue) {
    $dominantDemo = 'Kids';
    $dominantValue = $demographics['Kids'];
}

// Fallback in case of zero sales
if ($dominantValue == 0 && $totalSales == 0) {
    $dominantValue = 0;
    $dominantDemo = 'No Data';
}

renderHeader(true);
?>

<style>
    /* ======================================================= */
    /* -------------------- ENHANCED ANALYSIS STYLES --------- */
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

    .back {
      transition: all 0.3s ease;
      border-radius: 10px;
      padding: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      text-decoration: none;
    }

    .back:hover {
      background: rgba(0, 184, 212, 0.1);
      transform: translateX(-2px);
    }

    /* Enhanced KPI Cards */
    .kpi-row {
      margin-bottom: 32px;
      display: flex;
      gap: 20px;
    }

    .kpi {
      flex: 1;
      position: relative;
      overflow: hidden;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: none;
      box-shadow: 0 4px 20px rgba(0, 184, 212, 0.08);
      padding: 28px 24px;
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      animation: slideUp 0.5s ease-out;
      animation-fill-mode: both;
      border-radius: 16px;
    }

    .kpi:nth-child(1) { animation-delay: 0.1s; }
    .kpi:nth-child(2) { animation-delay: 0.2s; }
    .kpi:nth-child(3) { animation-delay: 0.3s; }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .kpi::before {
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

    .kpi:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(0, 184, 212, 0.15);
    }

    .kpi:hover::before {
      transform: scaleX(1);
    }

    .kpi .label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .kpi .value {
      font-size: 36px;
      font-weight: 900;
      background: linear-gradient(135deg, var(--dark) 0%, var(--teal-600) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 8px 0;
      line-height: 1.2;
    }

    .kpi .note {
      font-size: 13px;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Enhanced Cards */
    .grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .analysis-card, .card {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.1);
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 20px;
    }

    .analysis-card::before, .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .analysis-card:hover, .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(0, 184, 212, 0.12);
    }

    .section-title {
      font-size: 20px;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 24px;
      position: relative;
      padding-bottom: 12px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
      border-radius: 2px;
    }

    /* Enhanced Donut Chart */
    .donut-area {
      padding: 8px 0;
      display: flex;
      align-items: center;
      gap: 40px;
    }

    .donut {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: conic-gradient(
        var(--teal-600) 0% <?= $demographics['Men'] ?>%, 
        #EC4899 <?= $demographics['Men'] ?>% <?= $demographics['Men'] + $demographics['Women'] ?>%, 
        #F59E0B <?= $demographics['Men'] + $demographics['Women'] ?>% 100%
      );
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      animation: rotateIn 0.8s ease-out;
    }

    .donut-center {
        width: 110px;
        height: 110px;
        background: white;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    @keyframes rotateIn {
      from { opacity: 0; transform: rotate(-180deg) scale(0.8); }
      to { opacity: 1; transform: rotate(0) scale(1); }
    }

    .donut-center .big {
      font-size: 28px;
      font-weight: 900;
      color: var(--dark);
    }

    .donut-center .small {
      font-size: 13px;
      font-weight: 600;
      color: var(--muted);
    }

    .gender-mini {
      display: flex;
      height: 8px;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .gender-seg {
      transition: transform 0.2s ease;
    }

    .legend {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .legend .item {
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.2s ease;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .legend .item:hover { background: rgba(0, 184, 212, 0.05); }
    .swatch { width: 14px; height: 14px; border-radius: 4px; }
    .men-color { background: var(--teal-600); }
    .women-color { background: #EC4899; }
    .kids-color { background: #F59E0B; }

    /* Enhanced Product Cards */
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-top: 20px;
    }

    .product {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.1);
      border-radius: 16px;
      padding: 16px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .product:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 24px rgba(0, 184, 212, 0.15);
      border-color: rgba(0, 184, 212, 0.3);
    }

    .product img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .product .pname {
      font-weight: 700;
      font-size: 14px;
      color: var(--dark);
      margin-bottom: 6px;
      line-height: 1.4;
    }

    .product .pmeta {
      font-size: 12px;
      font-weight: 600;
      color: var(--teal-600);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Right Column Cards */
    .right-column .card ul { list-style: none; padding-left: 0; }
    .right-column .card ul li {
      padding: 10px 0 10px 24px;
      position: relative;
      transition: all 0.2s ease;
      border-radius: 6px;
      margin-bottom: 4px;
      color: var(--muted);
      line-height: 1.6;
    }

    .right-column .card ul li::before {
      content: '•';
      position: absolute;
      left: 8px;
      color: var(--teal-600);
      font-weight: bold;
      font-size: 18px;
    }

    .flex { display: flex; align-items: center; padding: 10px 12px; border-radius: 8px; }
    .flex:hover { background: rgba(0, 184, 212, 0.05); }

    @media (max-width: 1024px) { .grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
      .kpi-row { flex-direction: column; }
      .donut-area { flex-direction: column; }
    }
  </style>

  <main class="main" role="main">
    <div class="container">
      <header class="topbar">
        <div style="display:flex; align-items:center;">
          <a class="back" href="<?= BASE_URL ?>/brandowner.php">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <div class="title">Analysis Dashboard</div>
        </div>
      </header>

      <section class="kpi-row">
        <div class="kpi">
          <div class="label"><i class="fas fa-dollar-sign" style="color: var(--teal-600);"></i> Total Sales</div>
          <div class="value">EGP <?= number_format($totalSales, 2) ?></div>
          <div class="note">Total revenue from completed orders</div>
        </div>

        <div class="kpi">
          <div class="label"><i class="fas fa-users" style="color: var(--teal-600);"></i> Customers</div>
          <div class="value"><?= number_format($totalCustomers) ?></div>
          <div class="note">Unique customers who purchased</div>
        </div>

        <div class="kpi">
          <div class="label"><i class="fas fa-redo" style="color: var(--teal-600);"></i> Returning</div>
          <div class="value"><?= $returningRate ?>%</div>
          <div class="note">Repeat purchase rate</div>
        </div>
      </section>

      <div class="grid">
        <div>
          <section class="analysis-card">
            <h3 class="section-title"><i class="fas fa-users" style="color: var(--teal-600); margin-right: 10px;"></i> Customer Demographics</h3>

            <div class="donut-area">
              <div class="donut">
                <div class="donut-center">
                  <div class="big"><?= $dominantValue ?>%</div>
                  <div class="small"><?= $dominantDemo ?></div>
                </div>
              </div>

              <div style="flex:1;min-width:220px">
                <div style="margin-bottom:8px; color: var(--muted); font-weight: 600;">Gender distribution by items sold</div>
                <div class="gender-mini">
                  <div class="gender-seg men-color" style="width: <?= $demographics['Men'] ?>%;" title="Men <?= $demographics['Men'] ?>%"></div>
                  <div class="gender-seg women-color" style="width: <?= $demographics['Women'] ?>%;" title="Women <?= $demographics['Women'] ?>%"></div>
                  <div class="gender-seg kids-color" style="width: <?= $demographics['Kids'] ?>%;" title="Kids <?= $demographics['Kids'] ?>%"></div>
                </div>

                <div class="legend">
                  <div class="item"><span class="swatch men-color"></span><span>Men <strong style="margin-left:6px;color:var(--dark)"><?= $demographics['Men'] ?>%</strong></span></div>
                  <div class="item"><span class="swatch women-color"></span><span>Women <strong style="margin-left:6px;color:var(--dark)"><?= $demographics['Women'] ?>%</strong></span></div>
                  <div class="item"><span class="swatch kids-color"></span><span>Kids <strong style="margin-left:6px;color:var(--dark)"><?= $demographics['Kids'] ?>%</strong></span></div>
                </div>
              </div>
            </div>
          </section>

          <section class="analysis-card">
            <h3 class="section-title"><i class="fas fa-star" style="color: var(--teal-600); margin-right: 10px;"></i> Top Selling Products</h3>
            <div class="products">
              <?php if (empty($topProducts)): ?>
                  <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: var(--muted); border: 1px dashed var(--divider); border-radius: 12px;">
                      No products sold yet.
                  </div>
              <?php else: ?>
                  <?php foreach ($topProducts as $product): ?>
                      <div class="product">
                        <img src="<?= rtrim(BASE_URL, '/') ?><?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='<?= BASE_URL ?>/assets/images/shop/Harbaa.png'">
                        <div class="pname"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="pmeta"><i class="fas fa-check"></i> <?= number_format($product['sold_count']) ?> units sold</div>
                      </div>
                  <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </section>
        </div>

        <aside class="right-column">
          <div class="card">
            <h4 class="section-title"><i class="fas fa-lightbulb" style="color: var(--teal-600); margin-right: 8px;"></i> Quick Insights</h4>
            <div style="color: var(--muted); font-size: 13px;">Automated insights based on your data.</div>
            <ul>
              <?php if ($totalSales > 0): ?>
                  <li><strong>Revenue:</strong> You have generated EGP <?= number_format($totalSales) ?> in total sales.</li>
                  <li><strong>Loyalty:</strong> <?= $returningRate ?>% of your customers have purchased more than once.</li>
                  <?php if (!empty($topProducts)): ?>
                      <li><strong>Bestseller:</strong> Your top product is "<?= htmlspecialchars($topProducts[0]['name']) ?>". Consider restocking!</li>
                  <?php endif; ?>
              <?php else: ?>
                  <li>No data available yet. Start promoting your shop to get your first sales!</li>
              <?php endif; ?>
            </ul>
          </div>

          <div class="card">
            <h4 class="section-title"><i class="fas fa-funnel-dollar" style="color: var(--teal-600); margin-right: 8px;"></i> Sales Pipeline</h4>
            <div style="margin-top:8px; color: var(--muted); font-size: 13px;">Estimated conversion metrics.</div>
            <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
              <div class="flex">
                <div style="width:36px;background:var(--teal-300);height:12px;border-radius:6px;margin-right:8px"></div>
                <div style="color:var(--muted);">Store Visitors <strong style="margin-left:8px;color:var(--dark)">~<?= number_format($totalCustomers * 4) ?></strong></div>
              </div>
              <div class="flex">
                <div style="width:28px;background:#EC4899;height:12px;border-radius:6px;margin-right:8px"></div>
                <div style="color:var(--muted);">Added to Cart <strong style="margin-left:8px;color:var(--dark)">~<?= number_format($totalCustomers * 2) ?></strong></div>
              </div>
              <div class="flex">
                <div style="width:18px;background:#3B82F6;height:12px;border-radius:6px;margin-right:8px"></div>
                <div style="color:var(--muted);">Purchased <strong style="margin-left:8px;color:var(--dark)"><?= number_format($totalCustomers) ?></strong></div>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </main>
</body>
</html>

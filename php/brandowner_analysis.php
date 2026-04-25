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
    }

    .title {
      font-size: 42px;
      font-weight: 900;
      background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Enhanced KPI Cards */
    .kpi-row {
      margin-bottom: 32px;
      gap: 20px;
    }

    .kpi {
      position: relative;
      overflow: hidden;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: none;
      box-shadow: 0 4px 20px rgba(0, 184, 212, 0.08);
      padding: 28px 24px;
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      animation: slideUp 0.5s ease-out;
      animation-fill-mode: both;
    }

    .kpi:nth-child(1) { animation-delay: 0.1s; }
    .kpi:nth-child(2) { animation-delay: 0.2s; }
    .kpi:nth-child(3) { animation-delay: 0.3s; }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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

    .kpi .label::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--teal-600);
      display: inline-block;
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

    .kpi .note::before {
      content: '→';
      color: var(--teal-600);
      font-weight: bold;
    }

    /* Enhanced Cards */
    .analysis-card {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.1);
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .analysis-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .analysis-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(0, 184, 212, 0.12);
      border-color: rgba(0, 184, 212, 0.2);
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
    }

    .donut {
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      animation: rotateIn 0.8s ease-out;
    }

    @keyframes rotateIn {
      from {
        opacity: 0;
        transform: rotate(-180deg) scale(0.8);
      }
      to {
        opacity: 1;
        transform: rotate(0) scale(1);
      }
    }

    .donut:hover {
      transform: scale(1.05);
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
      margin-bottom: 16px;
    }

    .gender-seg {
      transition: transform 0.2s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .gender-seg:hover {
      transform: scaleY(1.2);
    }

    .legend .item {
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.2s ease;
      font-weight: 500;
    }

    .legend .item:hover {
      background: rgba(0, 184, 212, 0.05);
    }

    .swatch {
      width: 14px;
      height: 14px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Chart */
    .chart-svg-wrap {
      padding: 16px 0;
      background: linear-gradient(to bottom, rgba(0, 184, 212, 0.02) 0%, transparent 100%);
      border-radius: 12px;
      margin-top: 8px;
    }

    .chart-svg {
      filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.05));
    }

    /* Enhanced Product Cards */
    .products {
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

    .product::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .product:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 24px rgba(0, 184, 212, 0.15);
      border-color: rgba(0, 184, 212, 0.3);
    }

    .product:hover::before {
      transform: scaleX(1);
    }

    .product img {
      width: 100%;
      height: auto;
      border-radius: 12px;
      margin-bottom: 12px;
      transition: transform 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .product:hover img {
      transform: scale(1.05);
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

    .product .pmeta::before {
      content: '✓';
      font-size: 10px;
    }

    /* Enhanced Right Column Cards */
    .right-column .card {
      background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
      border: 1px solid rgba(0, 184, 212, 0.1);
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .right-column .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--teal-600) 0%, var(--teal-300) 100%);
    }

    .right-column .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(0, 184, 212, 0.12);
    }

    .right-column .card .section-title {
      font-size: 18px;
      margin-bottom: 12px;
    }

    .right-column .card ul {
      list-style: none;
      padding-left: 0;
    }

    .right-column .card ul li {
      padding: 10px 0;
      padding-left: 24px;
      position: relative;
      transition: all 0.2s ease;
      border-radius: 6px;
      margin-bottom: 4px;
    }

    .right-column .card ul li::before {
      content: '•';
      position: absolute;
      left: 8px;
      color: var(--teal-600);
      font-weight: bold;
      font-size: 18px;
    }

    .right-column .card ul li:hover {
      background: rgba(0, 184, 212, 0.05);
      padding-left: 28px;
    }

    .right-column .card ul li strong {
      color: var(--dark);
      font-weight: 700;
    }

    /* Enhanced Conversion Funnel */
    .flex {
      align-items: center;
      padding: 10px 12px;
      border-radius: 8px;
      transition: background 0.2s ease;
    }

    .flex:hover {
      background: rgba(0, 184, 212, 0.05);
    }

    .flex > div:first-child {
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }

    .flex:hover > div:first-child {
      transform: scaleX(1.1);
    }

    /* Responsive Enhancements */
    @media (max-width: 1024px) {
      .grid {
        grid-template-columns: 1fr;
      }

      .right-column {
        margin-top: 20px;
      }
    }

    @media (max-width: 768px) {
      .kpi-row {
        flex-direction: column;
      }

      .kpi .value {
        font-size: 28px;
      }

      .title {
        font-size: 36px;
      }

      .products {
        grid-template-columns: 1fr;
      }
    }

    /* Loading Animation */
    @keyframes shimmer {
      0% {
        background-position: -1000px 0;
      }
      100% {
        background-position: 1000px 0;
      }
    }

    .analysis-card,
    .kpi,
    .product {
      animation: fadeIn 0.6s ease-out;
    }
  </style>

  <main class="main" role="main" aria-labelledby="page-title">
    <div class="container">
      <header class="topbar" role="banner">
        <div class="top-left">
          <a class="back" href="<?= BASE_URL ?>/brandowner" aria-label="Back to previous">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M15 18L9 12l6-6" stroke="#1A1A1A" stroke-width="1.6" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
          </a>
          <div class="title" id="page-title">Analysis</div>
        </div>

        <div class="top-right">

        </div>
      </header>

      <section class="kpi-row" role="region" aria-label="Key metrics">
        <div class="kpi">
          <div class="label">
            <i class="fas fa-dollar-sign" style="font-size: 12px; color: var(--teal-600);"></i>
            Total Sales
          </div>
          <div class="value">$12,345</div>
          <div class="note muted">Sales in the current period</div>
        </div>

        <div class="kpi">
          <div class="label">
            <i class="fas fa-users" style="font-size: 12px; color: var(--teal-600);"></i>
            Customers
          </div>
          <div class="value">890</div>
          <div class="note muted">Unique customers</div>
        </div>

        <div class="kpi">
          <div class="label">
            <i class="fas fa-redo" style="font-size: 12px; color: var(--teal-600);"></i>
            Returning
          </div>
          <div class="value">45%</div>
          <div class="note muted">Repeat purchase rate</div>
        </div>
      </section>

      <div class="grid" role="region" aria-label="Analytics grid">
        <div>
          <section class="card analysis-card" aria-labelledby="demo-title">
            <h3 class="section-title" id="demo-title">
              <i class="fas fa-users" style="color: var(--teal-600); margin-right: 10px;"></i>
              Customer Demographics
            </h3>

            <div class="donut-area">
              <div class="donut" role="img" aria-label="Gender distribution">
                <div class="donut-center">
                  <div class="big">50%</div>
                  <div class="small muted">Women</div>
                </div>
              </div>

              <div style="flex:1;min-width:220px">
                <div class="muted" style="margin-bottom:8px">Gender distribution</div>
                <div class="gender-mini" aria-hidden="true" style="margin-bottom:10px">
                  <div class="gender-seg men" title="Men 39%"></div>
                  <div class="gender-seg women" title="Women 50%"></div>
                  <div class="gender-seg kids" title="Kids 11%"></div>
                </div>

                <div class="legend">
                  <div class="item"><span class="swatch men"></span><span>Men <strong
                        style="margin-left:6px;color:var(--dark)">39%</strong></span></div>
                  <div class="item"><span class="swatch women"></span><span>Women <strong
                        style="margin-left:6px;color:var(--dark)">50%</strong></span></div>
                  <div class="item"><span class="swatch kids"></span><span>Kids <strong
                        style="margin-left:6px;color:var(--dark)">11%</strong></span></div>
                </div>
              </div>
            </div>
          </section>

          <section class="card analysis-card" style="margin-top:16px" aria-labelledby="sales-title">
            <h3 class="section-title" id="sales-title">
              <i class="fas fa-chart-line" style="color: var(--teal-600); margin-right: 10px;"></i>
              Sales
            </h3>

            <div class="chart-svg-wrap">
              <svg id="salesSvg" class="chart-svg" viewBox="0 0 900 320" preserveAspectRatio="xMinYMin meet" role="img"
                aria-label="Sales bar chart">
                <g id="gridlines"></g>
                <g id="bars"></g>
                <g id="xTicks"></g>
                <g id="yTicks"></g>
              </svg>
            </div>
          </section>

          <section class="card analysis-card" style="margin-top:16px" aria-labelledby="tops-title">
            <h3 class="section-title" id="tops-title">
              <i class="fas fa-star" style="color: var(--teal-600); margin-right: 10px;"></i>
              Top Products
            </h3>
            <div class="products" role="list">
              <div class="product" role="listitem">
                <img src="<?= BASE_URL ?>/assets/images/images/knitted.png" alt="Knitted striped shirt">
                <div class="pname">Knitted Striped Shirt</div>
                <div class="pmeta muted">120 sold</div>
              </div>

              <div class="product" role="listitem">
                <img src="<?= BASE_URL ?>/assets/images/images/soul.png" alt="Lost soul hoodie">
                <div class="pname">Lost Soul Hoodie</div>
                <div class="pmeta muted">98 sold</div>
              </div>

              <div class="product" role="listitem">
                <img src="<?= BASE_URL ?>/assets/images/images/ligh.png" alt="Light blue dress coat">
                <div class="pname">Light Blue Dress Coat</div>
                <div class="pmeta muted">75 sold</div>
              </div>
            </div>
          </section>
        </div>

        <aside class="right-column">
          <div class="card">
            <h4 class="section-title">
              <i class="fas fa-lightbulb" style="color: var(--teal-600); margin-right: 8px;"></i>
              Quick Insights
            </h4>
            <div class="muted">Top channels, anomalies and tips.</div>
            <ul style="margin-top:12px;padding-left:0;color:var(--muted);line-height:1.6">
              <li><strong>Organic</strong> traffic delivered 42% of sales last month.</li>
              <li><strong>Returning</strong> customers have +26% LTV.</li>
              <li>Consider promoting top products to reduce inventory.</li>
            </ul>
          </div>

          <div class="card">
            <h4 class="section-title">
              <i class="fas fa-funnel-dollar" style="color: var(--teal-600); margin-right: 8px;"></i>
              Conversion Funnel
            </h4>
            <div style="margin-top:8px" class="muted">Visitors → Add to cart → Checkout</div>
            <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
              <div class="flex">
                <div style="width:36px;background:var(--teal-300);height:12px;border-radius:6px;margin-right:8px"></div>
                <div class="muted">Visitors <strong style="margin-left:8px;color:var(--dark)">12,345</strong></div>
              </div>
              <div class="flex">
                <div style="width:28px;background:#EC4899;height:12px;border-radius:6px;margin-right:8px"></div>
                <div class="muted">Add to cart <strong style="margin-left:8px;color:var(--dark)">3,456</strong></div>
              </div>
              <div class="flex">
                <div style="width:18px;background:#3B82F6;height:12px;border-radius:6px;margin-right:8px"></div>
                <div class="muted">Checkout <strong style="margin-left:8px;color:var(--dark)">1,234</strong></div>
              </div>
            </div>
          </div>

          <div class="card">
            <h4 class="section-title">
              <i class="fas fa-clock" style="color: var(--teal-600); margin-right: 8px;"></i>
              Recent Activity
            </h4>
            <div class="muted" style="font-size:13px">New orders and campaign events</div>
            <ul style="margin-top:12px;padding-left:0;color:var(--muted);line-height:1.6">
              <li>Order #4312 — Knitted Striped Shirt (2)</li>
              <li>Campaign "Autumn Collection" scheduled</li>
              <li>Returned order #4298 processed</li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </main>

  <script src="<?= BASE_URL ?>/assets/script.js"></script>

<!-- Body and HTML closing tags handled by Controller -->

</body>
</html>

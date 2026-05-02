<?php
chdir(__DIR__);

if (isset($_GET['run'])) {
    $base   = '..\\vendor\\bin\\phpunit';
    $groups = [
        'happy_path' => ['label' => 'Happy Path',  'icon' => '🟢'],
        'edge_case'  => ['label' => 'Edge Cases',  'icon' => '🟡'],
        'negative'   => ['label' => 'Negative',    'icon' => '🔴'],
        'no_crash'   => ['label' => 'No Crashes',  'icon' => '🔵'],
    ];

    $results   = [];
    $totals    = ['tests' => 0, 'passed' => 0, 'failed' => 0, 'assertions' => 0];

    foreach ($groups as $groupKey => $groupMeta) {
        $junitFile = __DIR__ . "/junit_{$groupKey}.xml";
        $cmd = "$base -c ..\\phpunit.xml --group $groupKey --log-junit " . escapeshellarg($junitFile) . " 2>&1";
        exec($cmd, $out, $code);

        $groupData = [
            'label'  => $groupMeta['label'],
            'icon'   => $groupMeta['icon'],
            'suites' => [],
            'passed' => 0,
            'failed' => 0,
        ];

        if (file_exists($junitFile)) {
            $xml = simplexml_load_file($junitFile);

            // PHPUnit 9 JUnit XML is 3 levels deep:
            // <testsuites>
            //   <testsuite name="">              ← level 1 (anonymous)
            //     <testsuite name="Dolaby Unit Tests">   ← level 2 (config suite)
            //       <testsuite name="ClassName"> ← level 3 (actual classes)
            //         <testcase .../>
            $classSuites = $xml->testsuite->testsuite->testsuite ?? null;

            if ($classSuites) {
                foreach ($classSuites as $suite) {
                    $suiteName  = (string)$suite['name'];
                    $suiteTests = [];
                    foreach ($suite->testcase as $tc) {
                        $hasFail = isset($tc->failure) || isset($tc->error);
                        $suiteTests[] = [
                            'name'   => (string)$tc['name'],
                            'passed' => !$hasFail,
                            'time'   => round((float)$tc['time'] * 1000, 2),
                            'msg'    => $hasFail ? ((string)($tc->failure ?? $tc->error)) : '',
                        ];
                        if ($hasFail) $groupData['failed']++;
                        else          $groupData['passed']++;
                    }
                    if (!empty($suiteTests)) {
                        $groupData['suites'][$suiteName] = $suiteTests;
                    }
                }
            }
            unlink($junitFile);
        }

        $totals['tests']      += $groupData['passed'] + $groupData['failed'];
        $totals['passed']     += $groupData['passed'];
        $totals['failed']     += $groupData['failed'];

        // parse assertions from CLI output
        $rawOut = implode("\n", $out);
        if (preg_match('/(\d+) assertion/', $rawOut, $m)) {
            $totals['assertions'] += (int)$m[1];
        }

        $results[$groupKey] = $groupData;
        $out = [];
    }

    echo json_encode([
        'totals'  => $totals,
        'results' => $results,
        'status'  => $totals['failed'] === 0 ? 'pass' : 'fail',
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolaby SQA — Testing Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --bg:#0f1117;--surface:#1a1d27;--surface2:#22263a;--border:#2e3249;
            --primary:#6366f1;--primary-glow:rgba(99,102,241,.25);
            --success:#10b981;--success-bg:rgba(16,185,129,.12);
            --danger:#ef4444;--danger-bg:rgba(239,68,68,.12);
            --warning:#f59e0b;--warning-bg:rgba(245,158,11,.12);
            --info:#06b6d4;--info-bg:rgba(6,182,212,.12);
            --text:#e2e8f0;--muted:#64748b;--dim:#94a3b8;
        }
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;padding:32px 24px}
        .wrap{max-width:1140px;margin:0 auto}

        /* HEADER */
        .hdr{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;margin-bottom:32px}
        .hdr h1{font-size:26px;font-weight:800;background:linear-gradient(135deg,#a5b4fc,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .hdr p{color:var(--muted);font-size:13px;margin-top:4px}
        .run-btn{display:flex;align-items:center;gap:8px;background:var(--primary);color:#fff;border:none;padding:11px 22px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;box-shadow:0 4px 20px var(--primary-glow);transition:.2s}
        .run-btn:hover{background:#5254c8;transform:translateY(-2px)}
        .run-btn:disabled{background:#374151;cursor:not-allowed;transform:none;box-shadow:none}
        .spinner{width:15px;height:15px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* STAT CARDS */
        .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
        @media(max-width:640px){.stats{grid-template-columns:repeat(2,1fr)}}
        .sc{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center;transition:border-color .3s}
        .sc:hover{border-color:var(--primary)}
        .sc .val{font-size:38px;font-weight:800;line-height:1;margin-bottom:5px}
        .sc .lbl{font-size:12px;color:var(--muted);font-weight:500;text-transform:uppercase;letter-spacing:.5px}
        .sc.t .val{color:#a5b4fc} .sc.p .val{color:var(--success)} .sc.f .val{color:var(--danger)} .sc.a .val{color:var(--warning)}

        /* PROGRESS BAR */
        .pbar-wrap{height:6px;background:var(--surface2);border-radius:3px;margin-bottom:28px;overflow:hidden}
        .pbar{height:100%;border-radius:3px;width:0;transition:width .9s ease}
        .pbar.pass{background:linear-gradient(90deg,var(--success),#34d399)}
        .pbar.fail{background:linear-gradient(90deg,var(--danger),#f87171)}

        /* OVERALL BADGE */
        .badge{display:none;align-items:center;gap:10px;padding:13px 18px;border-radius:12px;font-weight:600;font-size:14px;margin-bottom:28px;border:1px solid;animation:fadeUp .4s ease}
        .badge.pass{background:var(--success-bg);border-color:var(--success);color:var(--success)}
        .badge.fail{background:var(--danger-bg);border-color:var(--danger);color:var(--danger)}
        @keyframes fadeUp{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}

        /* CHECKLIST TABLE */
        .checklist{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:22px;margin-bottom:28px}
        .checklist h2{font-size:15px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th{text-align:left;padding:9px 14px;color:var(--muted);font-weight:600;border-bottom:1px solid var(--border)}
        td{padding:9px 14px;border-bottom:1px solid var(--border);color:var(--dim);vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:var(--surface2)}
        .tag{display:inline-block;padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600;margin:2px}
        .tag-h{background:rgba(129,140,248,.15);color:#a5b4fc}
        .tag-e{background:var(--warning-bg);color:var(--warning)}
        .tag-n{background:var(--danger-bg);color:#fca5a5}
        .tag-c{background:var(--info-bg);color:#67e8f9}

        /* CATEGORY TABS */
        .tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
        .tab{padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--border);background:var(--surface);color:var(--dim);transition:.2s}
        .tab:hover,.tab.active{border-color:var(--primary);color:var(--primary);background:var(--primary-glow)}

        /* CATEGORY PANELS */
        .cat-panel{display:none;animation:fadeUp .3s ease}
        .cat-panel.active{display:block}

        /* SUITE CARDS */
        .suite-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;margin-bottom:16px;overflow:hidden}
        .suite-hdr{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .2s}
        .suite-hdr:hover{background:var(--surface2)}
        .suite-left{display:flex;align-items:center;gap:12px}
        .suite-name{font-size:15px;font-weight:700}
        .suite-meta{font-size:12px;color:var(--muted);margin-top:2px}
        .suite-badge{font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px}
        .suite-badge.pass{background:var(--success-bg);color:var(--success)}
        .suite-badge.fail{background:var(--danger-bg);color:var(--danger)}
        .chevron{color:var(--muted);font-size:16px;transition:transform .3s;user-select:none}
        .chevron.open{transform:rotate(180deg)}

        /* TEST ITEMS */
        .suite-tests{display:none;padding:0 14px 14px}
        .suite-tests.open{display:block}
        .ti{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border-radius:8px;margin-top:8px;border:1px solid transparent;font-size:13px}
        .ti.pass{background:var(--success-bg);border-color:rgba(16,185,129,.15)}
        .ti.fail{background:var(--danger-bg);border-color:rgba(239,68,68,.2)}
        .ti-icon{font-size:14px;flex-shrink:0;margin-top:1px}
        .ti-body{flex:1}
        .ti-name{color:var(--dim);line-height:1.5}
        .ti-time{font-size:11px;color:var(--muted);margin-top:2px}
        .ti-msg{font-size:11px;color:#fca5a5;margin-top:6px;background:rgba(239,68,68,.08);border-radius:5px;padding:6px 8px;white-space:pre-wrap;word-break:break-all;font-family:monospace}

        /* EMPTY / IDLE */
        .idle{text-align:center;padding:60px 20px;color:var(--muted)}
        .idle .big{font-size:48px;margin-bottom:16px}
        .idle p{font-size:15px}

        /* RAW LOG */
        .raw-toggle{font-size:12px;color:var(--primary);cursor:pointer;margin-top:14px;display:inline-block}
        .raw-log{display:none;margin-top:10px;background:#0a0c14;border:1px solid var(--border);border-radius:8px;padding:14px;font-family:monospace;font-size:11px;color:#94a3b8;max-height:240px;overflow-y:auto;white-space:pre-wrap;word-break:break-all}

        .footer{text-align:center;margin-top:40px;font-size:12px;color:var(--muted)}

        /* CATEGORY COLOR ACCENTS */
        .cat-happy  .suite-card{border-left:3px solid #818cf8}
        .cat-edge   .suite-card{border-left:3px solid var(--warning)}
        .cat-neg    .suite-card{border-left:3px solid var(--danger)}
        .cat-crash  .suite-card{border-left:3px solid var(--info)}
    </style>
</head>
<body>
<div class="wrap">

    <div class="hdr">
        <div>
            <h1>🛡️ Dolaby SQA Dashboard</h1>
            <p>Software Quality Assurance — Unit Testing Suite — Categorized by Checklist</p>
        </div>
        <button id="runBtn" class="run-btn" onclick="runTests()">
            <span class="spinner" id="spinner"></span>
            <span id="btnText">▶ Run All Tests</span>
        </button>
    </div>

    <!-- STATS -->
    <div class="stats">
        <div class="sc t"><div class="val" id="sTotal">—</div><div class="lbl">Total Tests</div></div>
        <div class="sc p"><div class="val" id="sPassed">—</div><div class="lbl">✓ Passed</div></div>
        <div class="sc f"><div class="val" id="sFailed">—</div><div class="lbl">✗ Failed</div></div>
        <div class="sc a"><div class="val" id="sAssert">—</div><div class="lbl">Assertions</div></div>
    </div>

    <div class="pbar-wrap"><div class="pbar" id="pbar"></div></div>
    <div class="badge" id="badge"></div>

    <!-- CHECKLIST TABLE -->
    <div class="checklist">
        <h2>📋 Testing Checklist Coverage</h2>
        <table>
            <thead>
                <tr><th>Test Suite</th><th>Source Files</th><th>Key Logic Tested</th><th>Checklist</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>AnalyticsServiceTest</strong></td>
                    <td>brandowner_analysis.php</td>
                    <td>Sales totals, unique customers, demographics, top products</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
                <tr>
                    <td><strong>PromoCodeRepositoryTest</strong></td>
                    <td>brandowner_discount.php, checkout.php</td>
                    <td>% &amp; fixed discounts, max cap, exceeds total</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
                <tr>
                    <td><strong>LoyaltyServiceTest</strong></td>
                    <td>orderconfirmation.php, loyality.php</td>
                    <td>Points math, Bronze→Diamond tiers, EGP value</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
                <tr>
                    <td><strong>OrderServiceTest</strong></td>
                    <td>myorders.php, checkout_process.php</td>
                    <td>Rating permissions by status, order total math</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
                <tr>
                    <td><strong>PaymentValidationTest</strong></td>
                    <td>addanothercard.php, paymentmethod.php, viewcard.php</td>
                    <td>Luhn algorithm, expiry date, card type, name</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
                <tr>
                    <td><strong>ReviewServiceTest</strong></td>
                    <td>submit_rating.php, myorders.php</td>
                    <td>Rating range 1–5, order ownership, new vs update</td>
                    <td><span class="tag tag-h">Happy</span><span class="tag tag-e">Edge</span><span class="tag tag-n">Negative</span><span class="tag tag-c">No Crash</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- RESULTS -->
    <div id="resultsArea">
        <div class="idle"><div class="big">🧪</div><p>Click <strong>▶ Run All Tests</strong> to execute the full suite.</p></div>
    </div>

    <div class="footer">Dolaby v2 SQA · PHPUnit 9.6 · PHP 8.0 · Testing Database: dolaby_v2_testing</div>
</div>

<script>
const CAT_META = {
    happy_path: { label:'🟢 Happy Path',  cls:'cat-happy', desc:'Normal inputs — expected behavior' },
    edge_case:  { label:'🟡 Edge Cases',  cls:'cat-edge',  desc:'Boundary values — min/max limits' },
    negative:   { label:'🔴 Negative',    cls:'cat-neg',   desc:'Invalid inputs — system must reject' },
    no_crash:   { label:'🔵 No Crashes',  cls:'cat-crash', desc:'Unexpected states — must not throw' },
};

const SUITE_ICONS = {
    AnalyticsServiceTest:'📊', PromoCodeRepositoryTest:'🎟️',
    LoyaltyServiceTest:'🏆',   OrderServiceTest:'📦',
    PaymentValidationTest:'💳', ReviewServiceTest:'⭐',
};

let cardCounter = 0;

async function runTests() {
    const btn    = document.getElementById('runBtn');
    const spin   = document.getElementById('spinner');
    const btnTxt = document.getElementById('btnText');
    const area   = document.getElementById('resultsArea');
    const badge  = document.getElementById('badge');
    const pbar   = document.getElementById('pbar');

    btn.disabled = true; spin.style.display = 'block'; btnTxt.textContent = 'Running…';
    badge.style.display = 'none'; pbar.style.width = '0'; pbar.className = 'pbar';
    area.innerHTML = '<div class="idle"><div class="big">⏳</div><p>Running 4 test categories, please wait…</p></div>';
    ['sTotal','sPassed','sFailed','sAssert'].forEach(id => document.getElementById(id).textContent = '—');
    cardCounter = 0;

    try {
        const resp = await fetch('test_dashboard.php?run=1');
        const data = await resp.json();
        const t = data.totals;

        // Stats
        document.getElementById('sTotal').textContent  = t.tests;
        document.getElementById('sPassed').textContent = t.passed;
        document.getElementById('sFailed').textContent = t.failed;
        document.getElementById('sAssert').textContent = t.assertions;

        // Progress bar
        const pct = t.tests > 0 ? (t.passed / t.tests * 100).toFixed(1) : 0;
        pbar.style.width = pct + '%';
        pbar.className   = 'pbar ' + (t.failed > 0 ? 'fail' : 'pass');

        // Badge
        badge.style.display = 'flex';
        if (data.status === 'pass') {
            badge.className = 'badge pass';
            badge.innerHTML = '✅ All ' + t.tests + ' tests passed — ' + pct + '% success rate';
        } else {
            badge.className = 'badge fail';
            badge.innerHTML = '❌ ' + t.failed + ' test(s) failed — ' + pct + '% passed';
        }

        // Build tabs + panels
        const keys = Object.keys(data.results);
        let tabsHtml = '<div class="tabs">';
        keys.forEach((k, i) => {
            const m = CAT_META[k];
            const cnt = data.results[k].passed + data.results[k].failed;
            tabsHtml += `<div class="tab${i===0?' active':''}" onclick="switchTab('${k}')" id="tab-${k}">${m.label} <span style="opacity:.7;font-weight:400">(${cnt})</span></div>`;
        });
        tabsHtml += '</div>';

        let panelsHtml = '';
        keys.forEach((k, i) => {
            const cat     = data.results[k];
            const meta    = CAT_META[k];
            const allPass = cat.failed === 0;

            panelsHtml += `<div class="cat-panel ${meta.cls}${i===0?' active':''}" id="panel-${k}">`;
            panelsHtml += `<p style="color:var(--dim);font-size:13px;margin-bottom:16px">${meta.desc}</p>`;

            const suiteNames = Object.keys(cat.suites);
            if (suiteNames.length === 0) {
                panelsHtml += '<div class="idle" style="padding:30px"><p>No tests in this category.</p></div>';
            } else {
                suiteNames.forEach(suiteName => {
                    const tests   = cat.suites[suiteName];
                    const pass    = tests.filter(t => t.passed).length;
                    const fail    = tests.length - pass;
                    const icon    = SUITE_ICONS[suiteName] || '🔬';
                    const sid     = 'sc-' + (cardCounter++);
                    const allP    = fail === 0;

                    panelsHtml += `
                    <div class="suite-card">
                        <div class="suite-hdr" onclick="toggleCard('${sid}')">
                            <div class="suite-left">
                                <span style="font-size:20px">${icon}</span>
                                <div>
                                    <div class="suite-name">${suiteName}</div>
                                    <div class="suite-meta">${tests.length} test${tests.length!==1?'s':''} · ${pass} passed · ${fail} failed</div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px">
                                <span class="suite-badge ${allP?'pass':'fail'}">${allP?'✓ PASS':'✗ FAIL'}</span>
                                <span class="chevron open" id="chev-${sid}">▾</span>
                            </div>
                        </div>
                        <div class="suite-tests open" id="${sid}">`;

                    tests.forEach(test => {
                        const icon = test.passed ? '✓' : '✗';
                        const cls  = test.passed ? 'pass' : 'fail';
                        panelsHtml += `
                        <div class="ti ${cls}">
                            <span class="ti-icon">${icon}</span>
                            <div class="ti-body">
                                <div class="ti-name">${test.name}</div>
                                <div class="ti-time">${test.time} ms</div>
                                ${test.msg ? `<div class="ti-msg">${test.msg.substring(0,300)}</div>` : ''}
                            </div>
                        </div>`;
                    });

                    panelsHtml += '</div></div>';
                });
            }
            panelsHtml += '</div>';
        });

        area.innerHTML = tabsHtml + panelsHtml;

    } catch(err) {
        area.innerHTML = `<div class="suite-card" style="padding:24px"><p style="color:var(--danger)">⚠️ Error: ${err.message}</p></div>`;
    }

    btn.disabled = false; spin.style.display = 'none'; btnTxt.textContent = '▶ Run All Tests';
}

function switchTab(key) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.cat-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + key)?.classList.add('active');
    document.getElementById('panel-' + key)?.classList.add('active');
}

function toggleCard(sid) {
    const panel = document.getElementById(sid);
    const chev  = document.getElementById('chev-' + sid);
    panel?.classList.toggle('open');
    chev?.classList.toggle('open');
}

window.addEventListener('load', runTests);
</script>
</body>
</html>

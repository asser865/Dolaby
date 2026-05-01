<?php
// Ensure we are in the directory of the dashboard script
chdir(__DIR__);
$outputHtmlFile = __DIR__ . '/test_report.html';

// If this is an AJAX request to run tests, do it and exit
if (isset($_GET['run'])) {
    $command = '..\\vendor\\bin\\phpunit --testdox-html ' . escapeshellarg($outputHtmlFile) . ' 2>&1';
    exec($command, $output, $returnCode);

    $htmlContent = "";
    if (file_exists($outputHtmlFile)) {
        $rawHtml = file_get_contents($outputHtmlFile);
        if (preg_match('/<body>(.*?)<\/body>/is', $rawHtml, $matches)) {
            $htmlContent = $matches[1];
        } else {
            $htmlContent = str_replace(['<!doctype html>', '<html lang="en">', '<head>', '</head>', '<body>', '</body>', '</html>'], '', $rawHtml);
        }
    } else {
        $htmlContent = "<h2>No report generated. Check the logs.</h2><pre>" . implode("\n", $output) . "</pre>";
    }
    
    echo json_encode([
        'status' => $returnCode === 0 ? 'pass' : 'fail',
        'html' => $htmlContent
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolaby Unit Test Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dashboard {
            width: 100%;
            max-width: 900px;
            background: var(--surface);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        h1 {
            margin: 0;
            font-size: 28px;
            color: var(--text);
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: none; /* Hidden by default */
        }
        .status-pass { background-color: #d1fae5; color: #047857; }
        .status-fail { background-color: #fee2e2; color: #b91c1c; }
        .run-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .run-btn:hover { background-color: #4338ca; transform: translateY(-2px); }
        .run-btn:disabled { background-color: #94a3b8; cursor: not-allowed; transform: none; }
        
        .report-container {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #fafafa;
            min-height: 200px;
        }
        
        .loader {
            display: none;
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 18px;
        }

        /* Customize the injected PHPUnit TestDox styles */
        .report-container h2 { font-size: 20px; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 10px;}
        .report-container ul { list-style: none; padding-left: 0; }
        .report-container li { padding: 10px; margin-bottom: 8px; background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; align-items: center; }
        .report-container li::before { content: '✓'; color: var(--success); font-weight: bold; margin-right: 15px; font-size: 18px; }
        .report-container li.danger::before { content: '✗'; color: var(--danger); }
    </style>
</head>
<body>

    <div class="dashboard">
        <div class="header">
            <div>
                <h1>🛡️ Dolaby QA Dashboard</h1>
                <p style="color: var(--text-light); margin: 5px 0 0 0;">Automated Unit Testing Results</p>
            </div>
            <div>
                <button id="runTestsBtn" class="run-btn" onclick="runTests()">Run Tests</button>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <span id="statusBadge" class="status-badge"></span>
        </div>

        <div class="report-container">
            <div id="loader" class="loader">
                ⏳ Running tests, please wait...
            </div>
            <div id="reportContent">
                <p style="text-align:center; color:#64748b; margin-top: 40px;">Click "Run Tests" to execute the PHPUnit suite.</p>
            </div>
        </div>
    </div>

    <script>
        async function runTests() {
            const btn = document.getElementById('runTestsBtn');
            const loader = document.getElementById('loader');
            const content = document.getElementById('reportContent');
            const badge = document.getElementById('statusBadge');

            // Set loading state
            btn.disabled = true;
            btn.innerText = 'Running...';
            content.style.display = 'none';
            badge.style.display = 'none';
            loader.style.display = 'block';

            try {
                // Fetch test results dynamically
                const response = await fetch('test_dashboard.php?run=1');
                const data = await response.json();

                // Update UI with results
                content.innerHTML = data.html;
                
                // Update Badge
                badge.style.display = 'inline-block';
                if (data.status === 'pass') {
                    badge.className = 'status-badge status-pass';
                    badge.innerText = 'All Tests Passed! 🎉';
                } else {
                    badge.className = 'status-badge status-fail';
                    badge.innerText = 'Some Tests Failed ⚠️';
                }
            } catch (error) {
                content.innerHTML = '<h2 style="color:red;">Error running tests!</h2><p>' + error.message + '</p>';
            }

            // Reset loading state
            loader.style.display = 'none';
            content.style.display = 'block';
            btn.disabled = false;
            btn.innerText = 'Run Tests';
        }
        
        // Auto-run tests when page first loads
        window.onload = runTests;
    </script>

</body>
</html>

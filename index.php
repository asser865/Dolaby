<?php
/**
 * Main entry point - Simple routing
 */
require_once __DIR__ . '/includes.php';
require_once __DIR__ . '/header_functions.php';

// Get the route from URL
$url = $_GET['url'] ?? '';
if (empty($url) && isset($_SERVER['REQUEST_URI'])) {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestUri = strtok($requestUri, '?');
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $scriptName = str_replace('\\', '/', $scriptName);
    if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
        $url = substr($requestUri, strlen($scriptName));
    } else {
        $url = $requestUri;
    }
    $url = ltrim($url, '/');
    if (strpos($url, 'index.php') !== false) {
        $url = str_replace('index.php', '', $url);
        $url = ltrim($url, '/');
    }
}

$segments = array_filter(explode('/', $url));
$segments = array_values($segments);
$page = !empty($segments[0]) ? $segments[0] : 'homepage';
$action = !empty($segments[1]) ? $segments[1] : '';

// Route mapping
$routes = [
    '' => 'homepage.php',
    'homepage' => 'homepage.php',
    'home' => 'homepage.php',
    'login' => 'login.php',
    'register' => 'register.php',
    'forgotpassword' => 'forgotpassword.php',
    'forgot-password' => 'forgotpassword.php',
    'shop' => 'shop.php',
    'product' => 'product.php',
    'cart' => 'cart.php',
    'checkout' => 'checkout.php',
    'checkout_process' => 'checkout_process.php',
    'orderconfirmation' => 'orderconfirmation.php',
    'account' => 'account.php',
    'myprofile' => 'account.php',
    'accinfo' => 'accinfo.php',
    'changepassword' => 'changepassword.php',
    'changeemail' => 'changeemail.php',
    'settings' => 'settings.php',
    'deleteacc' => 'deleteacc.php',
    'loyality' => 'loyality.php',
    'loyalty' => 'loyality.php',
    'delivery' => 'delivery.php',
    'addnewadd' => 'addnewadd.php',
    'paymentmethod' => 'paymentmethod.php',
    'addanothercard' => 'addanothercard.php',
    'viewaddress' => 'viewaddress.php',
    'viewcard' => 'viewcard.php',
    'myorders' => 'myorders.php',
    'brandowner' => 'brandowner.php',
    'brand-owner' => 'brandowner.php',
    'brandowner_login' => 'brandowner_login.php',
    'brandowner_discount' => 'brandowner_discount.php',
    'brandowner_analysis' => 'brandowner_analysis.php',
    'menu' => 'menu.php',
    'aboutus' => 'aboutus.php',
    'about' => 'aboutus.php',
    'contactus' => 'contactus.php',
    'contact' => 'contactus.php',
    'helpfaqs' => 'helpfaqs.php',
    'help' => 'helpfaqs.php',
    'harbaabrand' => 'harbaabrand.php',
    'logout' => 'logout.php',
    'cart_add' => 'cart_add.php',
    'cart_update' => 'cart_update.php',
    'cart_remove' => 'cart_remove.php',
    'validate_promo' => 'validate_promo.php',
    'submit_rating' => 'submit_rating.php',
    'api_payment' => 'api_payment.php',
];

// Handle special routes
if ($page === 'order' && $action === 'confirmation') {
    $page = 'orderconfirmation';
} elseif ($page === 'order' && $action === 'myorders') {
    $page = 'myorders';
} elseif ($page === 'brandowner' && !empty($action)) {
    if ($action === 'login') {
        $page = 'brandowner_login';
    } elseif ($action === 'discount') {
        $page = 'brandowner_discount';
    } elseif ($action === 'analysis') {
        $page = 'brandowner_analysis';
    }
} elseif ($page === 'account' && !empty($action)) {
    // Handle account sub-pages
    if ($action === 'accinfo') {
        $page = 'accinfo';
    } elseif ($action === 'loyality' || $action === 'loyalty') {
        $page = 'loyality';
    } elseif ($action === 'changeemail') {
        $page = 'changeemail';
    } elseif ($action === 'changepassword') {
        $page = 'changepassword';
    } elseif ($action === 'delivery') {
        $page = 'delivery';
    } elseif ($action === 'paymentmethod') {
        $page = 'paymentmethod';
    } elseif ($action === 'addnewadd') {
        $page = 'addnewadd';
    } elseif ($action === 'addanothercard') {
        $page = 'addanothercard';
    } elseif ($action === 'viewaddress') {
        $page = 'viewaddress';
    } elseif ($action === 'viewcard') {
        $page = 'viewcard';
    } elseif ($action === 'settings') {
        $page = 'settings';
    } elseif ($action === 'deleteacc') {
        $page = 'deleteacc';
    }
} elseif ($page === 'cart' && $action === 'add') {
    $page = 'cart_add';
} elseif ($page === 'cart' && $action === 'update') {
    $page = 'cart_update';
} elseif ($page === 'cart' && $action === 'remove') {
    $page = 'cart_remove';
} elseif ($page === 'order' && $action === 'validatePromo') {
    $page = 'validate_promo';
} elseif ($page === 'order' && $action === 'submitRating') {
    $page = 'submit_rating';
} elseif ($page === 'order' && $action === 'doCheckout') {
    $page = 'checkout_process';
}

// Map page to file
$file = $routes[$page] ?? null;

if ($file) {
    $filePath = __DIR__ . '/php/' . $file;
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        http_response_code(404);
        echo "404: Page not found";
    }
} else {
    http_response_code(404);
    echo "404: Page not found";
}


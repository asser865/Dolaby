<?php
require_once __DIR__ . '/../includes.php';

// Clear all session data
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to homepage
header('Location: ' . BASE_URL);
exit;

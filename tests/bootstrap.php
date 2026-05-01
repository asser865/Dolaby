<?php
/**
 * PHPUnit Bootstrap file
 */
// Force testing environment database
define('DB_NAME', 'dolaby_v2_testing');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes.php';

// Define a test base class if needed
class TestCase extends PHPUnit\Framework\TestCase {
    // Add custom assertions or helper methods here
}

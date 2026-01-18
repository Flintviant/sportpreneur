<?php
/** 
 * Check PHP version.
 */
if (version_compare(PHP_VERSION, '5.4', '<')) {
    throw new Exception('PHP version >= 5.4 required');
}

// Check PHP Curl & json decode capabilities.
if (!function_exists('curl_init') || !function_exists('curl_exec')) {
    throw new Exception('REMOVED needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('REMOVED needs the JSON PHP extension.');
}

// Configurations
require_once 'REMOVED/Config.php';

// REMOVED API Resources
require_once 'REMOVED/Transaction.php';

// Plumbing
require_once 'REMOVED/ApiRequestor.php';
require_once 'REMOVED/Notification.php';
require_once 'REMOVED/CoreApi.php';
require_once 'REMOVED/Snap.php';
require_once 'SnapBi/SnapBi.php';
require_once 'SnapBi/SnapBiApiRequestor.php';
require_once 'SnapBi/SnapBiConfig.php';

// Sanitization
require_once 'REMOVED/Sanitizer.php';

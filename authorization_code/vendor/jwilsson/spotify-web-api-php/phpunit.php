<?php
error_reporting(-1);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');

set_error_handler('error_handler', E_ALL);

// Don't print errors for methods deprecated in the library
function error_handler($errno) {
    return $errno === E_USER_DEPRECATED;
}

// Test helper functions
function get_fixture($fixture)
{
    $fixture = __DIR__ . '/tests/fixtures/' . $fixture . '.json';
    $fixture = file_get_contents($fixture);

    return json_decode($fixture);
}

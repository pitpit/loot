<?php

if (!in_array(@$_SERVER['REMOTE_ADDR'], array(
    '127.0.0.1',
    '::1'
))) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$app = require __DIR__.'/../app/app.php';
$app->run();
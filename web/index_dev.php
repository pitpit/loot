<?php

//umask(0000);  //This will let the permissions be 0777

ini_set('display_errors', 1); 
error_reporting(E_ALL);

$app = require __DIR__.'/../app/app.php';
$app['debug'] = true;
$app['env'] = 'dev';
$app->run();
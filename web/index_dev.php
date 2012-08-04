<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

use Pitpit\Loot\Controller;

$app = require __DIR__.'/../app/app.php';
$app['debug'] = true;

Controller\ApiController::mount($app, '/api');
Controller\AppController::mount($app, '/app');


$app->run();
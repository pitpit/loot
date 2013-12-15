<?php

putenv('APP_ENV=prod');
$app = require __DIR__.'/../app/app.php';
$app->run();
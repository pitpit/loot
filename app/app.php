<?php

require_once __DIR__.'/../vendor/autoload.php';

//umask(0000); //This will let the permissions be 0777

$app = new \Loot\Application();
$app['env'] = getenv('APP_ENV') ?: 'dev';
$filenames = array(
    __DIR__.'/config/config.yml',
    __DIR__."/config/config_{$app['env']}.yml"
);
foreach ($filenames as $filename) {
    if (file_exists($filename)) {
        $app->register(new \Igorw\Silex\ConfigServiceProvider($filename));
    }
}
$app->register(new Loot\Api\Provider\MongoServiceProvider());
$app->register(new Loot\Api\Provider\ApiServiceProvider());
$app->register(new Loot\Api\Provider\ResourceServiceProvider());

$app->mount('/', new Loot\Api\Controller\EntryPointControllerProvider());
$app->mount('/', new Loot\Api\Controller\WorldControllerProvider());
// $app->mount('/admin', new Digitas\Admin\Controller\SecurityControllerProvider());
// $app->mount('/admin', new Digitas\Admin\Controller\DefaultControllerProvider());

return $app;

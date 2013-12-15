<?php

require_once __DIR__.'/../vendor/autoload.php';

umask(0000); //This will let the permissions be 0777

$app = new Silex\Application();

$app->register(new Digex\Provider\DigexServiceProvider());

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new Symfony\Component\Translation\Loader\YamlFileLoader());

    $filename = __DIR__ . '/trans/' . $app['locale'] . '.yml';
    if (file_exists($filename)) {
        $translator->addResource('yaml', $filename, $app['locale']);
    }

    return $translator;
}));

/** CUSTOMIZE HERE **/

$app->mount('/', new Digitas\Demo\Controller\DefaultControllerProvider());
$app->mount('/admin', new Digitas\Admin\Controller\SecurityControllerProvider());
$app->mount('/admin', new Digitas\Admin\Controller\DefaultControllerProvider());

return $app;

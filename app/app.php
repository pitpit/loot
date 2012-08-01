<?php

use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Pitpit\Silex\Provider\DoctrineORMServiceProvider;
use Pitpit\Silex\Provider\ConsoleServiceProvider;
use Pitpit\Silex\Provider\DoctrineSpatialServiceProvider;

$config = require 'config.php';

$app = new Silex\Application();

if (PHP_SAPI === 'cli') {
    $app->register(new ConsoleServiceProvider());
}

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => $config['db']['driver'],
        'dbname'    => $config['db']['name'],
        'host'      => $config['db']['host'],
        'user'      => $config['db']['user'],
        'password'  => $config['db']['password'],
    )
));

$app->register(new DoctrineORMServiceProvider(), array(
    'em.options' => array(
        'proxy_dir'         => __DIR__ . '/cache/proxies',
        'proxy_namespace'   => 'DoctrineORMProxy',
        'entities'          => $config['em']['entities']
    ),
    'em.fixtures'              => $config['em']['fixtures'],
));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => $config['translator']['locale_fallback']
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) use($config) {
    $translator->addLoader('yaml', new YamlFileLoader());

    foreach($config['translator']['locales'] as $locale => $filename) {
        $translator->addResource('yaml', __DIR__ . '/trans/' . $filename, $locale);
    }

    return $translator;
}));

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    'cache' => __DIR__ . '/cache/twig'
));

//set default locale
$app->before(function () use ($app) {
    if ($locale = $app['request']->get('locale')) {
        $app['locale'] = $locale;
    }
});

return $app;
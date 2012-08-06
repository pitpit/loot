<?php

use Silex\Provider as SilexProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Pitpit\Silex\Provider as PitpitProvider;

$config = require __DIR__ . '/config/config.php';

$app = new Silex\Application();

if (PHP_SAPI === 'cli') {
    $app->register(new PitpitProvider\ConsoleServiceProvider());
}

$app->register(new SilexProvider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => $config['db']['driver'],
        'dbname'    => $config['db']['name'],
        'host'      => $config['db']['host'],
        'user'      => $config['db']['user'],
        'password'  => $config['db']['password'],
    )
));

$app->register(new PitpitProvider\DoctrineORMServiceProvider(), array(
    'em.options' => array(
        'proxy_dir'         => __DIR__ . '/cache/proxies',
        'proxy_namespace'   => 'DoctrineORMProxy',
        'entities'          => $config['em']['entities']
    ),
    'em.fixtures'              => $config['em']['fixtures'],
));

$app->register(new PitpitProvider\DoctrineGeoServiceProvider());

$app->register(new SilexProvider\UrlGeneratorServiceProvider());

$app->register(new SilexProvider\TranslationServiceProvider(), array(
    'locale_fallback' => $config['translator']['locale_fallback']
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) use($config) {
    $translator->addLoader('yaml', new YamlFileLoader());

    foreach($config['translator']['locales'] as $locale => $filename) {
        $translator->addResource('yaml', __DIR__ . '/trans/' . $filename, $locale);
    }

    return $translator;
}));

$app->register(new SilexProvider\FormServiceProvider());

$app->register(new SilexProvider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    'cache' => __DIR__ . '/cache/twig'
));

// //set default locale
// $app->before(function () use ($app) {
//     if ($locale = $app['request']->get('locale')) {
//         $app['locale'] = $locale;
//     }
// });

return $app;
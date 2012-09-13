<?php

$app = new Silex\Application();

if (PHP_SAPI === 'cli') {
    $app->register(new Digex\Provider\ConsoleServiceProvider());
}

$app->register(new Digex\Provider\ConfigurationServiceProvider(), array(
    'config.config_dir'    => __DIR__ . '/config',
    'config.env'    => isset($env)?$env:null,
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => $app['config']['db']['driver'],
        'dbname'    => $app['config']['db']['name'],
        'host'      => $app['config']['db']['host'],
        'user'      => $app['config']['db']['user'],
        'password'  => $app['config']['db']['password'],
    )
));

$app->register(new Digex\Provider\DoctrineORMServiceProvider(), array(
    'em.options' => array(
        'proxy_dir'         => __DIR__ . '/cache/proxies',
        'proxy_namespace'   => 'DoctrineORMProxy',
        'entities'          => $app['config']['em']['entities']
    ),
    'em.fixtures'              => $app['config']['em']['fixtures'],
));

$app->register(new Pitpit\Geo\Provider\PostGisServiceProvider());

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => $app['config']['translator']['locale_fallback']
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) use($app) {
    $translator->addLoader('yaml', new Symfony\Component\Translation\Loader\YamlFileLoader());

    foreach($app['config']['translator']['locales'] as $locale => $filename) {
        $translator->addResource('yaml', __DIR__ . '/trans/' . $filename, $locale);
    }

    return $translator;
}));

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
    'cache' => __DIR__ . '/cache/twig',
    //'twig.form.templates' => array('Form/fields.html.twig')
));

//Set locale
$app->before(function () use ($app) {
    $locale = $app['request']->get('locale');
    if ($locale) { //ignore routes that do not support locale as parameter
        if (!isset($app['config']['translator']['locales'][$locale]) && $locale != $app['config']['translator']['locale_fallback']) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(sprintf('Locale "%s" is not supported', $locale));
        }
        $app['twig']->addGlobal('locale', $locale);
    }
});

//Register your controllers here...
$app->mount('/api', new Pitpit\Loot\Controller\ApiControllerProvider());
$app->mount('/', new Pitpit\Loot\Controller\AppsControllerProvider());

return $app;
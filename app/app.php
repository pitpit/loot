<?php

$app = new Silex\Application();

if (PHP_SAPI === 'cli') {
    $app->register(new Digex\Provider\ConsoleServiceProvider());
}

$app->register(new Digex\Provider\ConfigurationServiceProvider(), array(
    'config.config_dir'    => __DIR__ . '/config',
    'config.env'    => isset($env)?$env:null,
));
    
$app->register(new Silex\Provider\SessionServiceProvider());

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


$app->register(new Silex\Provider\SecurityServiceProvider());

$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
        'anonymous' => true,
    ),
    'secured' => array(
        'pattern' => '^/(dev|logout$|login_check$)',
        'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
        'logout' => array('logout_path' => '/logout'),
        'users' => $app->share(function () use ($app) {
            return new  Pitpit\Loot\Security\UserProvider($app['em']);
        }),
    ),
);

$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH', 'ROLE_DEVELOPER'),
    'ROLE_DEVELOPER' => array('ROLE_USER'),
);

$app['security.access_rules'] = array(
    array('^/dev', 'ROLE_DEVELOPER'),
);

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

// $app['locale'] = $app->share(function() use ($app) {
//     $locale = $app['request']->get('locale');
//     if ($locale) {
//         if (!isset($app['config']['translator']['locales'][$locale]) && $locale != $app['config']['translator']['locale_fallback']) {
//             throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(sprintf('Locale "%s" is not supported', $locale));
//         }
//         return $locale;
//     } else {
//         return $app['config']['translator']['locale_fallback'];
//     }
// });

//load current user from session & database
//@todo move it in a global place
$app['user'] = $app->share(function () use ($app) {
    $token = $app['security']->getToken();
    if (null === $token) {
        return null;
    }

    return $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneByEmail($token->getUser()->getUsername());
});

//Register your controllers here...
$app->mount('/api', new Pitpit\Loot\Controller\ApiControllerProvider());
$app->mount('/dev', new Pitpit\Loot\Controller\AppsControllerProvider());
$app->mount('/', new Pitpit\Loot\Controller\SecurityControllerProvider());

return $app;
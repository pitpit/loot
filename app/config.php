<?php

$config = array(
    'db' => array(
        'driver' => 'pdo_pgsql',
        'host' => 'localhost',
        'name' => 'loot',
        'user' => 'loot',
        'password' => 'loot'
    ),
    'em' => array(
        'entities' => array('src/Pitpit/Loot/Entity'),
        'fixtures' => array('src/Pitpit/Loot/DataFixtures')
    ),
    'translator' => array(
        'locale_fallback' => 'en_US',
        'locales' => array(
            'fr_FR' => 'fr_FR.yml'
        )
    )
);

return $config;
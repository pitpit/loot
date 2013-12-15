Digex
=====

Digex is a sandbox based on the micro-framework Silex (Symfony2 Components).

For core functionalies are implemented in [digex-core](https://github.com/digitas/digex-core).

If you have identified a bug in *Digex*, please use the [the bug tracker](https://github.com/digitas/digex/issues).

Getting Started
---------------

Creating a new project using composer :

    curl -s http://getcomposer.org/installer | php
    php composer.phar create-project digitas/digex project-directory -s dev

### Setup the sandbox

Allow apache user to write in cache and log directories:

    chmod 777 app/logs/ app/cache/

Every settings are stored in **app/config** directory.

The default settings are in **app/config/config.yml**.

The setting overrides dedicated to environment stand in corresponding **app/config/config_\<ENV\>.yml** file.

### Setup your server settings

Recommended PHP settings:

```
safe_mode = Off
register_globals = Off
short_open_tag = Off
magic_quotes_gpc = Off
magic_quotes_runtime = Off
session.auto_start = Off
date.timezone = "Europe/Paris"
phar.readonly = Off
phar.require_hash = Off
suhosin.executor.include.whitelist = phar
```

A virtual host sample

```
<VirtualHost *:80>
    ServerName [HOSTNAME]
    DocumentRoot "[ROOT_DIR]/web"

    <Directory "[ROOT_DIR]/web">
        AllowOverride all
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

### Run the sandbox

To check server requirements, visit the URL **http://.../check.php** to automatically check server requirements.

To run the application in DEV environment, visit **http://.../index_dev.php**.

To run the application in PROD environment, visit **http://.../index.php**.

Requirements
------------

### Server

* myqsl >= 5.1.x

* PHP >= 5.3.x
    * php-xml (libxml >= 2.7.x)
    * apc
    * Tokenizer
    * Mbstring
    * iconv (iconv >= 2.11.x)
    * XML
    * php_posix
    * intl
    * json
    * pdo
    * pdo_mysql
    * xsl
    * pcre
    * cURL
*apache >= 2.2.x
    * mod_rewrite

Copyright
---------

Copyright (c) 2011-2012, Digitas France
All rights reserved.

Silex is [copyrighted to Fabien Potencier and is licensed under the MIT license](https://raw.github.com/fabpot/Silex/master/LICENSE).

License
-------

Digex is released under the 3-clause BSD licence.

Please read the LICENSE file.

Contributors
------------

[See the Honour Roll](https://github.com/digitas/digex/graphs/contributors)

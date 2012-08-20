Loot
====

Requirements
------------

* postgresql >= 8.4
* postgis

Under Debian:
    
    apt-get install postgresql postgresql-8.4-postgis php5-pgsql
    /etc/init.d/apache2 restart

Installation
------------

### Database

Login with the postgres account:

    su postgres

Create the user:

    createuser loot -P -D -A

Create the database:

    createdb -O loot -E UNICODE loot

#### Enable Postgis with PostgreSQL < 9.1

Add the PL/pgSQL support to your database:

    createlang plpgsql loot

Load the PostGIS object and function definitions into your database

    psql -d loot -f /usr/share/postgresql/8.4/contrib/postgis-1.5/postgis.sql

For more information, read http://postgis.refractions.net/documentation/manual-2.0/postgis_installation.html#create_new_db

#### Enable Postgis with PostgreSQL >= 9.1

    psql -d loot -c "CREATE EXTENSION postgis;"

### Sources

Init vendor lib:

    curl -s https://getcomposer.org/installer | php

Init schema and create fixtures:

    php app/console doctrine:schema:create
    php app/console doctrine:fixtures:load

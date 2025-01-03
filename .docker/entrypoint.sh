#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data .

mkdir -p cache/acl cache/metaData cache/volt
chmod 777 cache -R

composer install

exec "$@"
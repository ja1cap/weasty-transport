#!/bin/bash

read -e -p "Enter environment:" -i "dev" ENV

php app/console assetic:dump -e ${ENV}
php app/console assets:install --symlink

rm -rf app/cache/${ENV}
php app/console cache:clear -e ${ENV}

chmod 0777 -R app/cache/
chmod 0777 -R app/logs/

service apache2 restart
service memcached restart
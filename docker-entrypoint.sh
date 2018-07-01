#!/usr/bin/env bash

cd /var/www/html
php ./app/generate-notification-yml.php
make migrate

exec /entrypoint.sh

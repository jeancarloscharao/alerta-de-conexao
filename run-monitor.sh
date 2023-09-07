#!/bin/bash

# Execute o comando
/usr/local/bin/php /var/www/html/src/index.php >> /var/log/cron.log 2>&1


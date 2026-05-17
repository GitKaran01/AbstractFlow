#!/bin/sh

# 1. Purani cache memory clear karo container reload hote waqt
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. PHP-FPM engine ko background daemon mode (-D) mein run karo
php-fpm -D

# 3. Nginx server ko main process (foreground) banakar chalu karo taaki container active rahe
echo "Launching Nginx Web Matrix Routing Layer..."
nginx -g "daemon off;"

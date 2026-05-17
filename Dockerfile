FROM php:8.2-fpm

# 1. System dependencies ke sath explicit NGINX bhi install karo
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# 2. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 3. Project files ko copy karo
COPY . .

# 4. PHP dependencies ko install karo
RUN composer install --no-dev --optimize-autoloader

# 5. Laravel public storage directory aur cache ke permissions correct karo
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 6. Nginx ki configuration file ko container mein copy karo (Step 2 mein hum ise banayenge)
COPY nginx.conf /etc/nginx/nginx.conf

# 7. Render ke default port (80) ko expose karo
EXPOSE 80

# 8. Container chalu hote hi PHP-FPM aur Nginx dono ko run karne ke liye script ko execute karo
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]

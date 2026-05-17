# =========================================================================
# 1. BASE SYSTEM LAYER (Ubuntu/Debian Based PHP 8.2 FPM)
# =========================================================================
FROM php:8.2-fpm

# System packages aur production Nginx core application package install karo
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

# =========================================================================
# 2. DEPENDENCY MANAGEMENT LAYER (Composer Setup)
# =========================================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure setup application directory path
WORKDIR /var/www

# Pure project folders aur code elements ko container memory grid me copy karo
COPY . .

# Production-ready vendors install optimization triggers
RUN composer install --no-dev --optimize-autoloader

# =========================================================================
# 3. PERMISSIONS & WEBSERVER CONFIGURATION STACK
# =========================================================================
# Laravel dynamic storage elements aur folder nodes ke core permissions secure karo
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Mapped centralized configuration profile inside container proxy nodes
COPY nginx.conf /etc/nginx/nginx.conf

# Render web component default routing port indicator exposure
EXPOSE 80

# =========================================================================
# 4. ORCHESTRATION STARTUP HANDLING (Windows Line Ending Fix Included)
# =========================================================================
# Pipeline shell scripts target paths mapping setup
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# 🔥 FOOLPROOF CRITICAL FIX: Windows CRLF (\r) invisible breaks clean automatic script
RUN sed -i 's/\r$//' /usr/local/bin/start.sh

# Master entrypoint orchestration command trigger sequence line
CMD ["/usr/local/bin/start.sh"]

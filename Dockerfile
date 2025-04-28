FROM php:8.1-fpm

# Cài đặt các phụ thuộc
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    librdkafka-dev

# Cài đặt PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài đặt PECL và ext-rdkafka
RUN pecl install rdkafka-6.0.3 && docker-php-ext-enable rdkafka

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Sao chép mã nguồn
WORKDIR /var/www/html
COPY . .

# Cài đặt dependencies
RUN composer install --optimize-autoloader --no-dev

# Quyền truy cập
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
FROM php:8.3-apache

# Обновляем пакеты и устанавливаем зависимости для компиляции расширений
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath

# Включаем mod_rewrite для Apache (нужен для Laravel)
RUN a2enmod rewrite

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем Node.js для сборки фронтенда (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Копируем проект
COPY . /var/www/html

# Устанавливаем зависимости и собираем фронтенд
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Настраиваем права доступа (важно для Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Открываем порт
EXPOSE 80

# Запускаем Apache
CMD ["apache2-foreground"]
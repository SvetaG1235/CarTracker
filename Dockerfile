FROM php:8.3-apache

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath

# Включаем mod_rewrite для Apache
RUN a2enmod rewrite

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем Node.js для сборки фронтенда (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Копируем проект
COPY . /var/www/html

# Устанавливаем зависимости
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Открываем порт
EXPOSE 80

# Запускаем Apache
CMD ["apache2-foreground"]
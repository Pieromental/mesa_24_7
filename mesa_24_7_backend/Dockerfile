FROM php:8.1-fpm

# 🧩 Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 📂 Establece el directorio de trabajo
WORKDIR /var/www

# 🧠 Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 📂 Copia todo el proyecto (incluyendo artisan)
COPY . .

# 🔐 Permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage

# 📦 Ahora sí: Instala dependencias
RUN composer install --optimize-autoloader --no-dev

# 🚀 Comando de inicio
CMD ["php-fpm"]

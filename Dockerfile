# Imagen base de PHP 7.4 con CLI
FROM php:7.4-cli

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_sqlite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# Instalar las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader



# Exponer el puerto 8000
EXPOSE 8000

# Comando por defecto para ejecutar el servidor de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

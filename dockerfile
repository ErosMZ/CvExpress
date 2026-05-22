FROM php:8.3-cli

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Carpeta app
WORKDIR /app

# Copiar archivos bhfv
COPY . .

# Instalar dependencias Laravel
RUN composer install

# Instalar frontend
RUN npm install && npm run build

# Cache Laravel
RUN php artisan config:cache

# Puerto Render
EXPOSE 10000

# Arranque
CMD php artisan serve --host=0.0.0.0 --port=10000
FROM php:8.3-cli

# Extensiones necesarias: pdo_pgsql (Supabase), zip (Composer),
# gd (dompdf: fotos en los PDFs de los CVs)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Instalación optimizada para producción (sin dependencias de desarrollo)
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN npm install && npm run build

EXPOSE 10000

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]

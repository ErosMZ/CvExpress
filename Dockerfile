FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

RUN php artisan migrate --force

RUN npm install && npm run build

EXPOSE 10000

CMD sh -c "php artisan serve --host=0.0.0.0 --port=$PORT"
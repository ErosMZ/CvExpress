FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

RUN npm install && npm run build

EXPOSE 10000

CMD sh -c "php artisan config:clear && php artisan storage:link --force && php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder --force && php artisan queue:work --daemon --sleep=3 --tries=3 & php artisan serve --host=0.0.0.0 --port=$PORT"
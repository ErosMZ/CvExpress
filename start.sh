#!/bin/sh
set -e

echo "==> Limpiando config..."
php artisan config:clear

echo "==> Creando storage:link..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Ejecutando migraciones..."
php artisan migrate --force

echo "==> Ejecutando seeder admin (idempotente)..."
php artisan db:seed --class=AdminUserSeeder --force

echo "==> Iniciando queue worker en segundo plano (con reinicio automático)..."
( while true; do
    php artisan queue:work --sleep=3 --tries=3 --max-time=3500 --quiet
    echo "    [queue] worker reiniciado"
    sleep 5
  done ) &

echo "==> Iniciando servidor web en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT

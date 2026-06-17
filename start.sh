#!/bin/sh
set -e

echo "==> Limpiando config..."
php artisan config:clear

echo "==> Creando storage:link..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Ejecutando migraciones..."
php artisan migrate --force

echo "==> Ejecutando seeder admin..."
php artisan db:seed --class=AdminUserSeeder --force

echo "==> Iniciando queue worker en segundo plano..."
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --quiet &
QUEUE_PID=$!
echo "    Queue worker PID: $QUEUE_PID"

echo "==> Iniciando servidor web en puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT

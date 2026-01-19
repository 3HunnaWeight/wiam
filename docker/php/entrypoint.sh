#!/bin/sh
set -e

if [ ! -d "/var/www/html/vendor" ]; then
  echo "Vendor not found, installing dependencies..."
  composer install --no-dev --optimize-autoloader
else
  echo "Vendor already exists"
fi

mkdir -p /var/www/html/runtime/logs
chmod -R 777 /var/www/html/runtime

attempt=1
while [ $attempt -le 20 ]; do
  if php /var/www/html/yii migrate --interactive=0 >/dev/null 2>&1; then
    break
  fi
  echo "Waiting for DB/migrations (attempt $attempt)..."
  attempt=$((attempt + 1))
  sleep 1
done


exec php-fpm

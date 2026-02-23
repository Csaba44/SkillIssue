#!/bin/sh
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY missing, refusing to start"
  exit 1
fi

echo "Running migrations (dev, seeded)..."
php artisan migrate --seed

exec "$@"
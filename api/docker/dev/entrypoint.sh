#!/bin/sh

echo "Running migrations (dev, seeded)..."
php artisan migrate --seed

exec "$@"
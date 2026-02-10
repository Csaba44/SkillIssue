#!/bin/sh

if [ ! -f .env ]; then
  echo ".env missing"
  exit 1
fi

if ! grep -q "^APP_KEY=base64:" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate
fi

exec "$@"
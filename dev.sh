#!/usr/bin/env bash
set -e

COMPOSE_FILE="docker-compose.dev.yml"
ENV_FILE=".env.dev"

UP_CMD="docker compose --env-file $ENV_FILE -f $COMPOSE_FILE up --build"
DOWN_CMD="docker compose --env-file $ENV_FILE -f $COMPOSE_FILE down -v"

if [[ "$1" == "-v" ]]; then
  echo ">> Removing volumes..."
  $DOWN_CMD
fi

echo ">> Starting compose - DEV mode..."
$UP_CMD

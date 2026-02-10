# -------------------------
# CONFIG
# -------------------------
COMPOSE_DEV=docker-compose.dev.yml
COMPOSE_PROD=docker-compose.prod.yml

VERSION ?= latest

BACKEND_IMAGE=skillissue-backend
FRONTEND_IMAGE=skillissue-frontend

# -------------------------
# HELP
# -------------------------
.PHONY: help
help:
	@echo ""
	@echo "Available commands:"
	@echo ""
	@echo "DEV:"
	@echo "  make dev-key        Generate Laravel APP_KEY (dev)"
	@echo "  make dev-up         Start dev stack (foreground)"
	@echo "  make dev-up-d       Start dev stack (detached)"
	@echo "  make dev-down       Stop dev stack"
	@echo "  make dev-down-v     Stop dev stack and REMOVE volumes (DB reset)"
	@echo ""
	@echo "BUILD:"
	@echo "  make build          Build all prod images (:latest)"
	@echo "  make build VERSION=x.y.z   Build and tag images with version"
	@echo ""
	@echo "PROD:"
	@echo "  make prod-up        Start prod stack (foreground)"
	@echo "  make prod-up-d      Start prod stack (detached)"
	@echo "  make prod-down      Stop prod stack"
	@echo "  make dev-down-v     Stop prod stack and REMOVE volumes (DB reset)"
	@echo ""
	@echo "UTILS:"
	@echo "  make logs           Tail prod logs"
	@echo "  make ps             Show running containers"
	@echo ""


# -------------------------
# DEV
# -------------------------
.PHONY: dev-key
dev-key:
	@echo "Generating Laravel APP_KEY (DEV)..."
	docker compose -f $(COMPOSE_DEV) run --rm artisan key:generate --show

.PHONY: dev-up
dev-up:
	docker compose -f $(COMPOSE_DEV) up --build

.PHONY: dev-up-d
dev-up-d:
	docker compose -f $(COMPOSE_DEV) up -d

.PHONY: dev-down
dev-down:
	docker compose -f $(COMPOSE_DEV) down

.PHONY: dev-down-v
dev-down-v:
	docker compose -f $(COMPOSE_DEV) down -v

# -------------------------
# BUILD (PROD)
# -------------------------
.PHONY: build
build:
	@echo "Building BACKEND image ($(VERSION))..."
	docker build -t $(BACKEND_IMAGE):$(VERSION) ./api
	docker tag $(BACKEND_IMAGE):$(VERSION) $(BACKEND_IMAGE):latest

	@echo "Building FRONTEND + NGINX image ($(VERSION))..."
	docker build -t $(FRONTEND_IMAGE):$(VERSION) --target prod ./frontend
	docker tag $(FRONTEND_IMAGE):$(VERSION) $(FRONTEND_IMAGE):latest

	@echo "Images built and tagged: $(VERSION), latest"


# -------------------------
# PROD
# -------------------------
.PHONY: prod-up
prod-up:
	docker compose -f $(COMPOSE_PROD) up

.PHONY: prod-up-d
prod-up-d:
	docker compose -f $(COMPOSE_PROD) up -d

.PHONY: prod-down
prod-down:
	docker compose -f $(COMPOSE_PROD) down

.PHONY: prod-down-v
prod-down-v:
	docker compose -f $(COMPOSE_PROD) down -v

# -------------------------
# UTILS
# -------------------------
.PHONY: logs
logs:
	docker compose -f $(COMPOSE_PROD) logs -f

.PHONY: ps
ps:
	docker compose ps

# SkillIssue

SkillIssue is a gamified quiz application developed as a final exam project for the software development and testing technician program at **Neumann János Gimnázium, Technikum és Kollégium**.

**Team members:** Csörgő Csaba, Flender Dávid

The project is inspired by games like Honfoglaló, GeoGuessr, and Duolingo — combining exam preparation with competitive game mechanics.

**Key features:**
- Solo practice mode
- Multiplayer 1v1 ranked system with matchmaking
- Question and user report system

---

## Developer Setup

### 1. Clone the repository

```bash
git clone https://github.com/Csaba44/SkillIssue.git
cd SkillIssue
```

### 2. Create `.env`

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Then fill in your user and group IDs to avoid file permission issues with Artisan:

```bash
id -u && id -g
```

```dotenv
# .env
UID=1000  # replace with output of id -u
GID=1000  # replace with output of id -g
```

### 3. Create `.env.dev`

Copy `.env.dev.example` to `.env.dev`:

```bash
cp .env.dev.example .env.dev
```

The file should look like this:

```dotenv
# BACKEND
APP_NAME=SkillIssue
APP_ENV=local
APP_KEY=                          # fill in after step 5
APP_DEBUG=true
APP_URL=http://127.0.0.1

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=skillissue
DB_USERNAME=root
DB_PASSWORD=root
MYSQL_ROOT_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=skillissue.local

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

SANCTUM_STATEFUL_DOMAINS=skillissue.local,skillissue.local:5173,skillissue.local:8080,127.0.0.1,localhost,nginx

# GAME MECHANISM
MAX_ROUNDS=5
VITE_MAX_ROUNDS=5
SOLO_MAX_GUESS_TIME=30
VITE_SOLO_MAX_GUESS_TIME=30
RANKED_MAX_GUESS_TIME=30
VITE_RANKED_MAX_GUESS_TIME=30

# SERVICE TO SERVICE COMMUNICATION
SERVICE_TOKEN=abcdefg123456

# FRONTEND
VITE_MAX_ROUNDS=5
```

### 4. Install dependencies

```bash
cd api && composer install
cd ../frontend && npm install
cd ../websocket && npm install
cd ..
```

### 5. Generate Laravel app key

```bash
make dev-key
```

This prints a key starting with `base64:...` — copy it into `.env.dev`:

```dotenv
APP_KEY=base64:...
```

> **Note:** The app key must start with `base64:` — if it doesn't, something went wrong.

### 6. Add local hostname

Add the following entry to your hosts file:

```bash
sudo nano /etc/hosts
```

```
127.0.0.1     skillissue.local
```

### 7. Start the dev stack

```bash
make dev-up
```

To stop:

```bash
make dev-down
```

The app will be available at: **http://skillissue.local:5173**

---

## Migrations

If migrations don't run automatically on startup:

```bash
make migrate-seed
```

---

## All available commands

```bash
make
```

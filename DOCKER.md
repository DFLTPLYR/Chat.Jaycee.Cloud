# Docker Setup Instructions

This project includes a complete Docker setup for development with Laravel, Inertia.js, Vue.js, and Redis.

## Prerequisites

- Docker
- Docker Compose

## Getting Started

1. **Clone the repository and navigate to the project directory**

2. **Copy the Docker environment file**

    ```bash
    cp .env.docker .env
    ```

3. **Generate application key**

    ```bash
    docker-compose run --rm app php artisan key:generate
    ```

4. **Build and start the containers**

    ```bash
    docker-compose up -d --build
    ```

5. **Install PHP dependencies**

    ```bash
    docker-compose exec app composer install
    ```

6. **Install Node.js dependencies**

    ```bash
    docker-compose exec node npm install
    ```

7. **Run database migrations**

    ```bash
    docker-compose exec app php artisan migrate
    ```

8. **Create storage link**
    ```bash
    docker-compose exec app php artisan storage:link
    ```

## Services

The Docker setup includes the following services:

- **app**: PHP 8.2-FPM container running Laravel
- **nginx**: Nginx web server (accessible at http://localhost:8000)
- **redis**: Redis server for caching and sessions
- **node**: Node.js container for Vite development server (HMR at http://localhost:5173)
- **queue**: Laravel queue worker

## Useful Commands

### Laravel Commands

```bash
# Run artisan commands
docker-compose exec app php artisan [command]

# Access app container shell
docker-compose exec app bash

# View logs
docker-compose logs app
```

### Frontend Development

```bash
# Install npm packages
docker-compose exec node npm install

# Run development server with HMR
docker-compose exec node npm run dev

# Build for production
docker-compose exec node npm run build
```

### Database

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# Fresh migration with seeding
docker-compose exec app php artisan migrate:fresh --seed
```

### Redis

```bash
# Connect to Redis CLI
docker-compose exec redis redis-cli
```

## Environment Variables

The `.env.docker` file is configured for Docker development. Key settings:

- Database: SQLite (stored in `/var/www/database/database.sqlite`)
- Cache: Redis
- Sessions: Redis
- Queue: Redis

## File Permissions

The containers are configured to handle file permissions automatically. If you encounter permission issues:

```bash
# Fix storage and cache permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

## Stopping the Services

```bash
# Stop all services
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

## Development Workflow

1. Make code changes in your local editor
2. Changes are automatically synced to containers via volumes
3. Vite HMR provides instant frontend updates
4. Laravel changes are reflected immediately (no container restart needed)

## Troubleshooting

### Container Issues

```bash
# Rebuild containers
docker-compose up -d --build --force-recreate

# View container logs
docker-compose logs [service-name]
```

### Permission Issues

```bash
# Reset permissions
docker-compose exec app chown -R www-data:www-data /var/www
```

### Database Issues

```bash
# Ensure SQLite file exists and has correct permissions
docker-compose exec app touch /var/www/database/database.sqlite
docker-compose exec app chown www-data:www-data /var/www/database/database.sqlite
```

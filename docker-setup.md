# Docker Setup for Laravel Kepegawaian Project

This project includes Docker configuration to run the Laravel application in containers.

## Prerequisites

- Docker Engine
- Docker Compose

## Quick Start

1. **Clone the repository** (if you haven't already):
   ```bash
   git clone <your-repo-url>
   cd web-kepegawaian
   ```

2. **Build and start the containers**:
   ```bash
   docker-compose up -d --build
   ```

3. **Install Laravel dependencies**:
   ```bash
   docker-compose exec app composer install
   ```

4. **Install frontend dependencies**:
   ```bash
   docker-compose exec app npm install
   ```

5. **Generate application key**:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Run database migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Build frontend assets** (if needed):
   ```bash
   docker-compose exec app npm run build
   ```

8. **Access the application**:
   - Application: [http://localhost:8000](http://localhost:8000)
   - MySQL: localhost:3306 (credentials in docker-compose.yml)
   - Redis: localhost:6379

## Additional Commands

- **Run artisan commands**:
  ```bash
  docker-compose exec app php artisan <command>
  ```

- **Run npm commands**:
  ```bash
  docker-compose exec app npm run dev  # for development
  docker-compose exec app npm run build  # for production build
  ```

- **Access the container**:
  ```bash
  docker-compose exec app bash
  ```

- **View logs**:
  ```bash
  docker-compose logs -f app
  ```

- **Stop the containers**:
  ```bash
  docker-compose down
  ```

- **Stop and remove volumes** (removes database):
  ```bash
  docker-compose down -v
  ```

## Services

- **app**: PHP 8.2 FPM container running the Laravel application
- **nginx**: Nginx web server serving the application
- **mysql**: MySQL 8.0 database server
- **redis**: Redis server for caching and queues

## Environment Configuration

The application uses the `.env.docker` file for configuration. You can customize it by copying it to `.env` and adjusting the values as needed.

## Troubleshooting

1. **Permission issues**: If you encounter permission issues with storage or bootstrap/cache directories:
   ```bash
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Database connection errors**: Make sure the MySQL container is running and the credentials in your .env file match those in docker-compose.yml.

3. **Frontend assets not loading**: Run the build command:
   ```bash
   docker-compose exec app npm run build
   ```

4. **If you get build errors**: Try building with the --no-cache flag:
   ```bash
   docker-compose build --no-cache
   ```
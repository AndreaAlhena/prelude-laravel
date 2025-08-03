# Docker Development Environment

This document describes how to set up and use the Docker development environment for the Prelude Laravel package.

## Prerequisites

- Docker Desktop or Docker Engine
- Docker Compose
- Make (optional, for convenience commands)

## Quick Start

1. **Build and start the environment:**
   ```bash
   make build
   make up
   ```

2. **Install dependencies:**
   ```bash
   make install
   ```

3. **Run tests:**
   ```bash
   make test
   ```

## Services

The Docker environment includes the following services:

### App Container
- **Image:** PHP 8.2 CLI
- **Purpose:** Main development environment
- **Port:** 8000
- **Volume:** Project files mounted to `/var/www`

### MySQL Database
- **Image:** MySQL 8.0
- **Purpose:** Database for testing
- **Port:** 3306
- **Credentials:**
  - Database: `prelude_test`
  - User: `prelude`
  - Password: `password`
  - Root Password: `root`

### Redis Cache
- **Image:** Redis 7 Alpine
- **Purpose:** Caching and session storage
- **Port:** 6379

### Composer
- **Image:** Composer latest
- **Purpose:** Dependency management
- **Profile:** tools

### Pest Testing
- **Image:** Custom PHP 8.2
- **Purpose:** Running tests
- **Profile:** testing

## Available Commands

### Docker Management
```bash
make build          # Build Docker containers
make up             # Start containers in background
make down           # Stop containers
make restart        # Restart containers
make logs           # Show container logs
make shell          # Access app container shell
make status         # Show container status
```

### Development
```bash
make install        # Install Composer dependencies
make update         # Update Composer dependencies
make composer CMD="require package"  # Run custom composer commands
```

### Testing
```bash
make test           # Run Pest tests
make test-coverage  # Run tests with HTML coverage report
make test-watch     # Run tests in watch mode
```

### Utilities
```bash
make clean          # Clean up Docker resources
make reset          # Reset entire environment
make help           # Show available commands
```

## Manual Docker Commands

If you prefer not to use Make:

```bash
# Start environment
docker-compose up -d

# Install dependencies
docker-compose run --rm composer install

# Run tests
docker-compose --profile testing run --rm pest

# Access shell
docker-compose exec app bash

# Stop environment
docker-compose down
```

## Development Workflow

1. **Start the environment:**
   ```bash
   make up
   ```

2. **Install dependencies (first time only):**
   ```bash
   make install
   ```

3. **Make your changes** to the source code

4. **Run tests:**
   ```bash
   make test
   ```

5. **Access container shell if needed:**
   ```bash
   make shell
   ```

## Configuration

### PHP Configuration
PHP settings can be customized in `docker/php/local.ini`:
- Memory limit: 512M
- Upload size: 40M
- Error reporting: Enabled
- Opcache: Enabled

### Xdebug (Optional)
Uncomment the Xdebug settings in `docker/php/local.ini` to enable debugging:
```ini
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
```

## Troubleshooting

### Port Conflicts
If ports 3306, 6379, or 8000 are already in use:
1. Stop conflicting services
2. Or modify ports in `docker-compose.yml`

### Permission Issues
If you encounter permission issues:
```bash
sudo chown -R $USER:$USER .
```

### Container Issues
To completely reset the environment:
```bash
make reset
```

### View Logs
To debug container issues:
```bash
make logs
# Or for specific service:
docker-compose logs app
```

## Performance Tips

1. **Use Docker volumes** for better performance on macOS/Windows
2. **Exclude unnecessary files** via `.dockerignore`
3. **Use multi-stage builds** for production images
4. **Enable BuildKit** for faster builds:
   ```bash
   export DOCKER_BUILDKIT=1
   ```

## IDE Integration

### VS Code
For optimal VS Code integration:
1. Install the "Remote - Containers" extension
2. Use the command palette: "Remote-Containers: Reopen in Container"

### PhpStorm
1. Configure Docker as PHP interpreter
2. Set up remote debugging with Xdebug
3. Configure database connection to localhost:3306
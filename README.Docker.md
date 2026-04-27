# FleetOps Backend - Docker Setup

This document explains how to run the FleetOps Backend API with Docker and SQL Server.

## Prerequisites

- Docker Desktop installed
- Docker Compose installed
- At least 4GB RAM available for Docker

## Quick Start

1. **Build and start the containers:**
   ```bash
   docker-compose up -d --build
   ```

2. **Wait for services to be ready** (about 30-60 seconds for SQL Server to initialize)

3. **Generate application key:**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

4. **Run database migrations:**
   ```bash
   docker-compose exec app php artisan migrate --force
   ```

5. **Access the API:**
   - API: http://localhost:8000
   - SQL Server: localhost:1433

## Container Management

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f sqlserver
```

### Stop containers
```bash
docker-compose down
```

### Stop and remove volumes (WARNING: deletes database data)
```bash
docker-compose down -v
```

### Restart a service
```bash
docker-compose restart app
```

### Execute commands in container
```bash
# Run artisan commands
docker-compose exec app php artisan [command]

# Access container shell
docker-compose exec app sh

# Access SQL Server
docker-compose exec sqlserver /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P "Fleetops12345678!" -C
```

## Performance Optimizations

This setup includes:

- **PHP OPcache** enabled with optimized settings
- **Nginx** with FastCGI caching and gzip compression
- **Alpine Linux** for minimal image size
- **Multi-stage builds** to reduce final image size
- **Optimized Composer** autoloader
- **Connection pooling** between Nginx and PHP-FPM

## Troubleshooting

### SQL Server connection issues
```bash
# Check SQL Server health
docker-compose exec sqlserver /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P "Fleetops12345678!" -C -Q "SELECT @@VERSION"
```

### Clear Laravel cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Rebuild containers
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## Production Deployment

For production, update:
1. Change SQL Server password in docker-compose.yml
2. Set APP_DEBUG=false in .env
3. Use proper SSL certificates for Nginx
4. Configure proper backup strategy for SQL Server volume
5. Set up monitoring and logging

## Database Backup

```bash
# Backup
docker-compose exec sqlserver /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P "Fleetops12345678!" -C -Q "BACKUP DATABASE fleetops TO DISK = '/var/opt/mssql/backup/fleetops.bak'"

# Copy backup to host
docker cp fleetops-sqlserver:/var/opt/mssql/backup/fleetops.bak ./backup/
```

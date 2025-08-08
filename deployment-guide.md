# Database Deployment Guide

## Current Database Setup
This gaming platform uses PostgreSQL database hosted on Replit with the following configuration:
- Database Type: PostgreSQL 16 (Neon-hosted)
- Connection via environment variables
- Schema includes: users, user_sessions tables

## Option 1: Connect External Host to Replit Database

### Get Connection Details
The database connection details are available as environment variables:
- `DATABASE_URL` - Complete connection string
- `PGHOST` - Database host
- `PGUSER` - Database username  
- `PGPASSWORD` - Database password
- `PGDATABASE` - Database name
- `PGPORT` - Database port

### External Host Configuration
```php
// For external PHP hosting, update config/database.php:
$host = 'your-replit-pghost';
$dbname = 'your-replit-dbname';
$username = 'your-replit-pguser';
$password = 'your-replit-pgpassword';
$port = 'your-replit-pgport';

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
```

## Option 2: Export and Migrate Database

### 1. Export Current Database
```bash
# Export schema and data
pg_dump $DATABASE_URL > gaming_platform_backup.sql
```

### 2. Set Up New Database
Create a PostgreSQL database on your hosting provider and import:
```bash
# On your new host
psql -h your-new-host -U your-new-user -d your-new-database < gaming_platform_backup.sql
```

### 3. Update Configuration
Update `config/database.php` with your new database credentials.

## Required Files for Hosting
- All PHP files (index.php, api/, config/, includes/, pages/)
- Assets (CSS, JS, images)
- .htaccess for URL rewriting
- Database schema (sql/setup_postgres.sql)

## Hosting Requirements
- PHP 8.0+ with PDO PostgreSQL extension
- PostgreSQL database (version 12+)
- Apache/Nginx with mod_rewrite support
- HTTPS support recommended for production

## Security Considerations
- Change default passwords
- Use environment variables for database credentials
- Enable HTTPS for production
- Configure proper file permissions
- Set up database backups
# Deployment Guide for IIAQATAR

This guide covers deploying the IIAQATAR Laravel application to production environments.

## Pre-Deployment Checklist

- [ ] All tests passing (`php artisan test`)
- [ ] Environment variables configured
- [ ] Database backups enabled
- [ ] SSL certificate installed
- [ ] Domain DNS configured
- [ ] Server meets minimum requirements
- [ ] Error logging configured
- [ ] Email service configured

## Server Requirements

### Minimum Requirements
- PHP 8.1 or higher
- MySQL 8.0+ or PostgreSQL 13+
- Composer 2.x
- Node.js 18+ and NPM
- Nginx or Apache
- SSL Certificate (Let's Encrypt recommended)
- At least 1GB RAM
- At least 10GB disk space

### Recommended Server Specifications
- PHP 8.2
- MySQL 8.0+
- 2GB+ RAM
- 20GB+ SSD storage
- Redis for caching
- Supervisor for queue workers

## Deployment Steps

### 1. Server Setup

#### Install Required Software

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
  php8.2-curl php8.2-zip php8.2-gd php8.2-redis -y

# Install MySQL
sudo apt install mysql-server -y

# Install Nginx
sudo apt install nginx -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Redis (optional but recommended)
sudo apt install redis-server -y
```

### 2. Database Setup

```bash
# Create database
sudo mysql -u root -p
```

```sql
CREATE DATABASE iiaqatar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'iiaqatar_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON iiaqatar.* TO 'iiaqatar_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/Idris7umed/iiaqatar.git
cd iiaqatar

# Set permissions
sudo chown -R www-data:www-data /var/www/iiaqatar
sudo chmod -R 755 /var/www/iiaqatar
sudo chmod -R 775 /var/www/iiaqatar/storage
sudo chmod -R 775 /var/www/iiaqatar/bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# Configure environment
cp .env.example .env
nano .env
```

#### Environment Configuration (.env)

```env
APP_NAME="IIAQATAR"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://iiaqatar.org

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iiaqatar
DB_USERNAME=iiaqatar_user
DB_PASSWORD=your_secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@iiaqatar.org"
MAIL_FROM_NAME="IIAQATAR"

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (optional, for initial data)
php artisan db:seed --force

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Create storage link
php artisan storage:link
```

### 4. Web Server Configuration

#### Nginx Configuration

Create `/etc/nginx/sites-available/iiaqatar.org`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name iiaqatar.org www.iiaqatar.org;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name iiaqatar.org www.iiaqatar.org;
    root /var/www/iiaqatar/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/iiaqatar.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/iiaqatar.org/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/iiaqatar.org /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d iiaqatar.org -d www.iiaqatar.org

# Test auto-renewal
sudo certbot renew --dry-run
```

### 6. Queue Workers (Optional)

If using queued jobs, set up Supervisor:

```bash
sudo apt install supervisor -y
```

Create `/etc/supervisor/conf.d/iiaqatar-worker.conf`:

```ini
[program:iiaqatar-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/iiaqatar/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/iiaqatar/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start iiaqatar-worker:*
```

### 7. Scheduled Tasks

Add to crontab:

```bash
sudo crontab -e -u www-data
```

Add line:

```
* * * * * cd /var/www/iiaqatar && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Deployment

### Security Hardening

1. **File Permissions**
   ```bash
   sudo chown -R www-data:www-data /var/www/iiaqatar
   sudo find /var/www/iiaqatar -type f -exec chmod 644 {} \;
   sudo find /var/www/iiaqatar -type d -exec chmod 755 {} \;
   sudo chmod -R 775 /var/www/iiaqatar/storage
   sudo chmod -R 775 /var/www/iiaqatar/bootstrap/cache
   ```

2. **Disable Directory Listing**
   Already handled in Nginx config

3. **Configure Firewall**
   ```bash
   sudo ufw allow 22/tcp
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```

4. **Set Up Database Backups**
   ```bash
   # Create backup script
   sudo nano /usr/local/bin/backup-iiaqatar-db.sh
   ```

   ```bash
   #!/bin/bash
   DATE=$(date +%Y%m%d_%H%M%S)
   BACKUP_DIR="/var/backups/iiaqatar"
   mkdir -p $BACKUP_DIR
   
   mysqldump -u iiaqatar_user -p'password' iiaqatar | gzip > $BACKUP_DIR/iiaqatar_$DATE.sql.gz
   
   # Keep only last 7 days
   find $BACKUP_DIR -type f -mtime +7 -delete
   ```

   ```bash
   sudo chmod +x /usr/local/bin/backup-iiaqatar-db.sh
   ```

   Add to crontab:
   ```
   0 2 * * * /usr/local/bin/backup-iiaqatar-db.sh
   ```

### Monitoring

1. **Application Monitoring**
   - Set up error logging to external service (Sentry, Bugsnag)
   - Monitor Laravel logs: `storage/logs/laravel.log`
   - Set up uptime monitoring

2. **Server Monitoring**
   ```bash
   # Install monitoring tools
   sudo apt install htop iotop -y
   ```

### Performance Optimization

1. **Enable OPcache**
   Edit `/etc/php/8.2/fpm/php.ini`:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.interned_strings_buffer=16
   opcache.max_accelerated_files=10000
   opcache.revalidate_freq=2
   ```

2. **Configure Redis Cache**
   Already set in `.env` file

3. **Enable Gzip Compression**
   Already enabled in Nginx config

## Updating the Application

```bash
cd /var/www/iiaqatar

# Put application in maintenance mode
php artisan down

# Pull latest changes
sudo -u www-data git pull origin main

# Update dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm ci
sudo -u www-data npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Bring application back online
php artisan up
```

## Rollback Procedure

If deployment fails:

```bash
# Revert to previous commit
sudo -u www-data git reset --hard HEAD~1

# Reinstall dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Rollback database if needed
php artisan migrate:rollback

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan up
```

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Check Nginx logs: `/var/log/nginx/error.log`
   - Verify file permissions
   - Check `.env` configuration

2. **Database Connection Issues**
   - Verify database credentials in `.env`
   - Check MySQL service: `sudo systemctl status mysql`
   - Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

3. **Permission Denied Errors**
   - Verify www-data ownership: `ls -la /var/www/iiaqatar`
   - Reset permissions using commands in Security Hardening section

4. **Queue Workers Not Processing**
   - Check Supervisor status: `sudo supervisorctl status`
   - Check worker logs: `tail -f storage/logs/worker.log`
   - Restart workers: `sudo supervisorctl restart iiaqatar-worker:*`

## Support

For deployment issues:
- Email: devops@iiaqatar.org
- Documentation: https://github.com/Idris7umed/iiaqatar

---

Last Updated: December 2024

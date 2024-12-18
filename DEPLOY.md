# Habibi Stay - Deployment Guide

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 16+
- MySQL 8.0+
- Redis
- Nginx
- Supervisor
- SSL Certificate

## Server Setup

1. **Update System**
```bash
sudo apt update && sudo apt upgrade -y
```

2. **Install Required Packages**
```bash
sudo apt install -y nginx mysql-server redis-server supervisor php8.2-fpm php8.2-mysql php8.2-redis php8.2-curl php8.2-xml php8.2-mbstring php8.2-zip
```

3. **Configure MySQL**
```bash
sudo mysql_secure_installation
```

4. **Configure Redis**
- Edit `/etc/redis/redis.conf`
- Set a password
- Enable AOF persistence

## Deployment Steps

1. **Clone Repository**
```bash
cd /var/www
git clone [repository-url] habibi-stay
cd habibi-stay
```

2. **Set Up Environment**
```bash
cp env.production.example .env
php artisan key:generate
```

3. **Install Dependencies**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

4. **Configure Nginx**
```bash
cp nginx.conf.example /etc/nginx/sites-available/habibi-stay
ln -s /etc/nginx/sites-available/habibi-stay /etc/nginx/sites-enabled/
```

5. **Configure SSL**
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

6. **Set Up Database**
```bash
php artisan migrate --force
```

7. **Configure Supervisor**
```bash
cp supervisor.conf.example /etc/supervisor/conf.d/habibi-stay.conf
supervisorctl reread
supervisorctl update
supervisorctl start habibi-stay-worker:*
```

8. **Set Permissions**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

9. **Cache Configuration**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Maintenance Mode

To put the application in maintenance mode:
```bash
php artisan down --secret="your-secret-token"
```

To bring the application back up:
```bash
php artisan up
```

## Monitoring

- Monitor logs in `/var/www/habibi-stay/storage/logs/`
- Monitor queue workers using `supervisorctl status`
- Set up monitoring tools like New Relic or Laravel Telescope

## Backup

1. **Database Backup**
```bash
mysqldump -u [user] -p [database] > backup.sql
```

2. **File Backup**
```bash
tar -czf backup.tar.gz /var/www/habibi-stay
```

## Security Checklist

- [ ] SSL certificate installed and configured
- [ ] Database credentials secured
- [ ] File permissions properly set
- [ ] Environment file secured
- [ ] Debug mode disabled
- [ ] Redis password set
- [ ] Firewall configured
- [ ] Regular security updates enabled
- [ ] Rate limiting configured
- [ ] CORS policies set
- [ ] Security headers configured

## Troubleshooting

1. **Check Logs**
```bash
tail -f storage/logs/laravel.log
```

2. **Check Queue Workers**
```bash
supervisorctl status
```

3. **Check Nginx Status**
```bash
systemctl status nginx
```

4. **Check PHP-FPM Status**
```bash
systemctl status php8.2-fpm
```

## Contact

For deployment issues, contact the development team at [contact-email]

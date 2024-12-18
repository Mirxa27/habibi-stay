#!/bin/bash

# Exit on error
set -e

echo "Starting deployment process..."

# Pull latest changes
echo "Pulling latest changes..."
git pull origin main

# Install/update Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Install/update NPM dependencies
echo "Installing NPM dependencies..."
npm install
npm run build

# Clear caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Restart queue workers
echo "Restarting queue workers..."
php artisan queue:restart

# Update storage link
echo "Updating storage links..."
php artisan storage:link

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Deployment completed successfully!"

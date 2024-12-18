#!/bin/bash

# Exit on error
set -e

echo "Starting deployment process..."

# Configuration
DEPLOY_PATH="/home/u221943340/public_html"
REPO_URL="https://github.com/Mirxa27/habibi-stay.git"

# Navigate to deployment directory
cd $DEPLOY_PATH

# Pull latest changes
if [ -d ".git" ]; then
    echo "Updating existing repository..."
    git pull origin main
else
    echo "Cloning repository..."
    git clone $REPO_URL .
fi

# Install/update Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Set up environment file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

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

# Set permissions
echo "Setting permissions..."
find storage bootstrap/cache -type f -exec chmod 664 {} \;
find storage bootstrap/cache -type d -exec chmod 775 {} \;

# Create storage link if it doesn't exist
php artisan storage:link

echo "Deployment completed successfully!"

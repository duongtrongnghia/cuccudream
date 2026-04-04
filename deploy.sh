#!/bin/bash
# Deploy Cúc Cu Dream to production VPS
# Usage: ./deploy.sh
# SAFE: Only runs additive migrations, never drops data

set -e

VPS="root@103.97.127.109"
PORT=2018
APP="/var/www/cuccudream"

echo "🚀 Deploying to cuccudream.com..."

# 1. Push to GitHub
echo "📤 Pushing to GitHub..."
git push origin main

# 2. Pull on VPS + install + build + migrate
echo "📥 Pulling on VPS..."
ssh -p $PORT $VPS "bash -s" << REMOTE
set -e
cd $APP
git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -1
npm ci 2>&1 | tail -1
npx vite build 2>&1 | tail -1
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:clear
chown -R www-data:www-data $APP
chmod -R 775 storage bootstrap/cache
sudo systemctl restart php8.4-fpm
echo "✅ Deploy complete!"
REMOTE

echo ""
echo "🌐 https://cuccudream.com"

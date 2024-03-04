@echo off
cd /
cd C:\xampp\htdocs\edmts
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan config:cache
php artisan cache:clear
php artisan serve --host=0.0.0.0 --port=9002

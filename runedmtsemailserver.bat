@echo off
cd /
cd C:\xampp\htdocs\edmts
php artisan queue:work --queue=high,default
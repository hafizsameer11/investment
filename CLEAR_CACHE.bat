@echo off
echo Clearing Laravel caches...
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
echo.
echo Running composer dump-autoload...
composer dump-autoload
echo.
echo All caches cleared!
echo Now start your server with: php artisan serve
pause



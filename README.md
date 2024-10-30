#INSTALL:
-PHP composer <8.0
-PHP 8

cd "event_management"

composer install

cd "./public"
php -S localhost:8000 

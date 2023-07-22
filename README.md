## FMS
Food Management System for restaurant handling food menus, receiving orders and staff details

### Steps for setup
- `composer update` or `composer install` - to install all the dependencies
- Copy `.env.example` to `.env` and put datebase name, password and other config details
- `php artisan key:generate` - to generate the app key
- `php artisan jwt:secret` - to generate jwt secret key
- `php artisan migrate` - for database migrations
- `php artisan db:seed` - seed for all the initial migrations

### API Doc
Used Scribe for APIDoc. The config files are located at `config/Scribe.php` and to generate APIDoc `php artisan scribe:generate` 

### New Routes not found
New routes are not detected during development. Run `composer run-script fresh` to clear all caches, routes.

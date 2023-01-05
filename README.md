## FMS
Food Management System for restaurant handling food menus, receiving orders and staff details

### Steps for setup
- `composer update` or `composer install` - to install all the dependencies
- `php artisan key:generate` - to generate the app key
- `php artisan jwt:secret` - to generate jwt secret key
- `php artisan migrate` - for database migrations
- `php artisan db:seed` - seed for all the initial migrations
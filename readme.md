# Sample chart App

A Laravel app built with AngularJS. For demonstration a chart of conversion on random data.

## Requirements

- PHP 5.5.9 or later
- mbstring (PHP module)
- composer

## Installation

1. Clone the repository: `git clone https://github.com/killbond/test_conversion.git`
2. jump to project directory
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. config your db connection in .env file
7. `$ php artisan migrate`
8. `$ php artisan db:seed`
9. `$ cd public`
10. run php built in web server
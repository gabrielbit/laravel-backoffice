Backoffice for Laravel 4
========================

This project exposes various services and objects to create and maintain a typical backoffice project.

## Usage
* Through the `Digbang\L4Backoffice\Backoffice` class
* Through `php artisan backoffice:*` commands (pending)

## Adding the service provider
Add this line to your `app/config/app.php` providers array:

```php
'providers' => array(
	...
	'Digbang\L4Backoffice\BackofficeServiceProvider'
	...
);
```

## Contributing
This project is being developed with [PHPSpec](http://phpspec.net).
It is recommended to generate specifications for each new feature added.
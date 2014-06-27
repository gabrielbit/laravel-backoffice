Backoffice for Laravel 4
========================

This project exposes various services and objects to create and maintain a typical backoffice project.

## Usage
* Through the `Digbang\L4Backoffice\Backoffice` class
* Through the code generator pages

## Adding the service provider
Add this line to your `app/config/app.php` providers array:

```php
'providers' => array(
	...
	'Digbang\L4Backoffice\BackofficeServiceProvider'
	...
);
```

## Using the gen
To use the code generator you need to also add some routes to your `routes.php` file:

```php
Route::get('gen',            'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection');
Route::post('gen/customize', 'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@customization');
Route::post('gen/generate',  'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@generation');
```

## Contributing
This project is being developed with [PHPSpec](http://phpspec.net).
It is recommended to generate specifications for each new feature added.
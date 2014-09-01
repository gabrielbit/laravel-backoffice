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
The code generator is available to projects in debug mode at the `/backoffice/gen` url.
This is included automatically by adding the `BackofficeServiceProvider` to your `app.php`
configuration file, so there is no need to change your routes.

## Authentication
Authentication is provided via the [digbang/security](http://git.digbang.com/digbang/security) package.
Auth urls are prefixed with `backoffice/auth/` so that they don't collide with each project urls.
To use this, your backoffice route group should include one of the provided backoffice filters:

* `backoffice.auth.logged` (only checks for guest / logged in users)
* `backoffice.auth.withPermissions` (also checks for permissions on each url)

The `digbang/security` package provides a `SecureUrl` object, that will help you build the urls that the
currently logged in user has permission to use.

You can customize your permissions repository by publishing the backoffice config files and editing the `auth.php`
file. By default, an `InsecurePermissionRepository` implementation is used.

## Contributing
This project is being developed with [PHPSpec](http://phpspec.net).
It is recommended to generate specifications for each new feature added.
Backoffice for Laravel 4
========================

This project exposes various services and objects to create and maintain a typical backoffice project.

## Usage
* Add it to your `composer.json`
* Add the service provider (see below)
* Run `php artisan backoffice:install`
* Run code generation through the code generator pages in `/backoffice/gen`
* Access the objects through the `Digbang\L4Backoffice\Backoffice` class

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
file. By default, a `RoutePermissionRepository` implementation is used.

## Users and Groups
The Backoffice will associate `users` and `groups` resources for you, and will be available through the
`/backoffice/backoffice-users` and `/backoffice/backoffice-groups` urls, respectively.

Use this CRUDs to administrate users and groups. Make sure only administrators can access this pages!

## Persistent URLs
The Backoffice can use persistent URLs to improve navigation (i.e.: Keeping filters used in listings).
To use this the route to persist should have the configuration option `persistent => true` and 
your backoffice route group should include the filter:

* `backoffice.urls.persist` (will save the url in session)

The package provides a `PersistentUrl` object, that will help you build the urls that are persisted (falling 
back to the original url if no session key is found. `PersistentUrl` will use `digbang/security` `SecureUrl` 
to build secure URLs. *Be aware that the object will only persist GET requests. All other methods will not get 
persisted, but will be handled transparently by `SecureUrl`.*

## Contributing
This project is being developed with [PHPSpec](http://phpspec.net).
It is recommended to generate specifications for each new feature added.
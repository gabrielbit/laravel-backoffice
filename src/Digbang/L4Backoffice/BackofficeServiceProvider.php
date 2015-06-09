<?php namespace Digbang\L4Backoffice;

use Digbang\FontAwesome\FontAwesomeServiceProvider;
use Digbang\L4Backoffice\Auth\Routes\AuthRouteBinder;
use Digbang\L4Backoffice\Auth\Routes\GroupsRouteBinder;
use Digbang\L4Backoffice\Auth\Routes\UsersRouteBinder;
use Digbang\L4Backoffice\Generator\Routes\GenRouteBinder;
use Digbang\Security\Filters\Auth;
use Digbang\Security\SecurityServiceProvider;
use Digbang\Security\SentryWithDoctrineServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Maatwebsite\Excel\ExcelServiceProvider;

/**
 * Class BackofficeServiceProvider
 * @package Digbang\L4Backoffice
 */
class BackofficeServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->package('digbang/l4-backoffice');

		/** @type Repository $config */
		$config = $this->app->make('config');

		/** @type Router $router */
		$router = $this->app->make(Router::class);

		$this->stringMacros(
			$this->app->make(Str::class),
			$this->app->make(Support\Str::class)
		);

		$this->registerCommands();

		$this->registerAuthRoutes($router);
		$this->registerAuthFilters($router);
		$this->registerUserGroupRoutes($router);

		if ($config->get('app.debug'))
		{
			$this->registerGenRoutes($router);
		}

		require_once 'composers.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(FontAwesomeServiceProvider::class);
		$this->app->register(ExcelServiceProvider::class);
		$this->app->register(SecurityServiceProvider::class);
		$this->app->register(SentryWithDoctrineServiceProvider::class);

		$this->app->singleton('menuFactory', Support\MenuFactory::class);
		$this->app->bind('linkMaker', Support\LinkMaker::class);
		$this->app->bind('Mustache_Engine', function(){
			return new \Mustache_Engine([
				'cache' => new \Mustache_Cache_FilesystemCache(storage_path('cache'))
			]);
		});
	}

	protected function stringMacros(Str $str, Support\Str $myStr)
	{
		$str->macro('titleFromSlug', [$myStr, 'titleFromSlug']);
		$str->macro('parse', [$myStr, 'parse']);
	}

	protected function registerGenRoutes(Router $router)
	{
		$this->app->make(GenRouteBinder::class)->bind($router);
	}

	protected function registerAuthRoutes(Router $router)
	{
		$this->app->make(AuthRouteBinder::class)->bind($router);
	}

	protected function registerAuthFilters(Router $router)
	{
		$router->filter('backoffice.auth.logged',          Auth::class . '@logged');
		$router->filter('backoffice.auth.withPermissions', Auth::class . '@withPermissions');
	}

	protected function registerCommands()
	{
		$this->commands([
			Commands\AuthGenerationCommand::class,
			Commands\InstallCommand::class
		]);
	}

	protected function registerUserGroupRoutes(Router $router)
	{
		$this->app->make(UsersRouteBinder::class)->bind($router);
		$this->app->make(GroupsRouteBinder::class)->bind($router);
	}
}
<?php namespace Digbang\L4Backoffice;

use Illuminate\Support\ServiceProvider;

/**
 * Class BackofficeServiceProvider
 * @package Digbang\L4Backoffice
 */
class BackofficeServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->package('digbang/l4-backoffice');

		$this->stringMacros();

		if (\Config::get('app.debug'))
		{
			$this->registerGenRoutes();
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
		$this->app->singleton('menuFactory', 'Digbang\L4Backoffice\Support\MenuFactory');
		$this->app->bind('linkMaker', 'Digbang\L4Backoffice\Support\LinkMaker');

		$this->app->register('Digbang\FontAwesome\FontAwesomeServiceProvider');

		$this->app->bind('Mustache_Engine', function(){
			return new \Mustache_Engine([
				'cache' => new \Mustache_Cache_FilesystemCache(storage_path() . DIRECTORY_SEPARATOR . 'cache')
			]);
		});
	}

	protected function stringMacros()
	{
		\Str::macro('titleFromSlug', function($slug){
			return \Str::title(str_replace(array('-', '_'), ' ', $slug));
		});
	}

	protected function registerGenRoutes()
	{
		/* @var $router \Illuminate\Routing\Router */
		$router = $this->app['router'];

		$router->group(['prefix' => 'backoffice'], function() use ($router){
			$router->get( 'gen',           'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection');
			$router->post('gen/customize', 'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@customization');
			$router->post('gen/generate',  'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@generation');
			$router->get( 'gen/generate',  'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@testGenerationPage');
		});
	}
} 
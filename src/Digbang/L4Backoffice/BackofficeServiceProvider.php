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
	}

	protected function stringMacros()
	{
		\Str::macro('titleFromSlug', function($slug){
			return \Str::title(str_replace(array('-', '_'), ' ', $slug));
		});

		
	}
} 
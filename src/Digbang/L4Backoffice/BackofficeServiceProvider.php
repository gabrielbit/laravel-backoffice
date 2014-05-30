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
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// TODO: Implement register() method.
	}

} 
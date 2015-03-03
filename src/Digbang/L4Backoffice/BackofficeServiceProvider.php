<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Repositories\DoctrineGroupRepository;
use Digbang\L4Backoffice\Repositories\DoctrineThrottleRepository;
use Digbang\L4Backoffice\Repositories\DoctrineUserRepository;
use Illuminate\Support\ServiceProvider;
use Cartalyst\Sentry\Users\ProviderInterface      as UserProvider;
use Cartalyst\Sentry\Groups\ProviderInterface     as GroupProvider;
use Cartalyst\Sentry\Throttling\ProviderInterface as ThrottleProvider;

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

		$this->registerCommands();
		$this->registerAuthRoutes();
		$this->registerAuthFilters();
		$this->registerUserGroupRoutes();

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
        $this->app->register('Digbang\Security\SecurityServiceProvider');
		$this->app->register('Maatwebsite\Excel\ExcelServiceProvider');

		$this->app->bind('Mustache_Engine', function(){
			return new \Mustache_Engine([
				'cache' => new \Mustache_Cache_FilesystemCache(storage_path('cache'))
			]);
		});

        /** @type \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        if ($config->get('security::auth.driver') == 'custom')
        {
            if (! isset($this->app[UserProvider::class]))
            {
                $this->app->bind(UserProvider::class, DoctrineUserRepository::class, true);
            }

            if (! isset($this->app[GroupProvider::class]))
            {
                $this->app->bind(GroupProvider::class, DoctrineGroupRepository::class, true);
            }
            if (! isset($this->app[ThrottleProvider::class]))
            {
                $this->app->bind(ThrottleProvider::class, DoctrineThrottleRepository::class, true);
            }
        }
	}

	protected function stringMacros()
	{
		/* @var $str \Illuminate\Support\Str */
		$str   = $this->app->make('Illuminate\Support\Str');
		$myStr = $this->app->make('Digbang\L4Backoffice\Support\Str');

		$str->macro('titleFromSlug', [$myStr, 'titleFromSlug']);
		$str->macro('parse', [$myStr, 'parse']);
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

	protected function registerAuthRoutes()
	{
		/* @var $router \Illuminate\Routing\Router */
		$router = $this->app['router'];

		$router->group(['prefix' => 'backoffice/auth'], function() use ($router){
			$authRoute      = 'backoffice.auth';
			$authController = 'Digbang\\L4Backoffice\\Auth\\AuthController';

			$router->get('login',                      ['as' => "$authRoute.login",                   'uses' => "$authController@login"]);
			$router->get('password/forgot',            ['as' => "$authRoute.password.forgot",         'uses' => "$authController@forgotPassword"]);
			$router->get('password/reset/{id}/{code}', ['as' => "$authRoute.password.reset",          'uses' => "$authController@resetPassword"]);
			$router->get('logout',                     ['as' => "$authRoute.logout",                  'uses' => "$authController@logout"]);
			$router->get('activate/{code}',            ['as' => "$authRoute.activate",                'uses' => "$authController@activate"]);

			$router->post('login',                     ['as' => "$authRoute.authenticate",            'uses' => "$authController@authenticate"]);
			$router->post('password/forgot',           ['as' => "$authRoute.password.forgot-request", 'uses' => "$authController@forgotPasswordRequest"]);
			$router->post('password/reset/{code}',     ['as' => "$authRoute.password.reset-request",  'uses' => "$authController@resetPasswordRequest"]);
		});
	}

	protected function registerAuthFilters()
	{
		/* @var $router \Illuminate\Routing\Router */
		$router = $this->app['router'];

		$router->filter('backoffice.auth.logged', 'Digbang\Security\Filters\Auth@logged');
		$router->filter('backoffice.auth.withPermissions', 'Digbang\Security\Filters\Auth@withPermissions');
	}

	protected function registerCommands()
	{
		$ns = 'Digbang\L4Backoffice\Commands';
		$this->commands([
			"$ns\\AuthGenerationCommand",
			"$ns\\InstallCommand"
		]);
	}

	protected function registerUserGroupRoutes()
	{
		/* @var $router \Illuminate\Routing\Router */
		$router = $this->app['router'];

		$router->group(['prefix' => 'backoffice', 'before' => 'backoffice.auth.withPermissions'], function() use ($router){
			$bkNamespace = "Digbang\\L4Backoffice\\Auth";

			foreach (['users' => 'User', 'groups' => 'Group'] as $path => $name)
			{
				$fullPath = "backoffice-$path";

				$router->group(['prefix' => $fullPath], function() use ($router, $name, $path, $fullPath, $bkNamespace) {
					$router->get("export",         ['as' => "backoffice.$fullPath.export",  "uses" => "$bkNamespace\\{$name}Controller@export",  "permission" => "backoffice.$fullPath.list"]);

					$router->get("/",              ["as" => "backoffice.$fullPath.index",   "uses" => "$bkNamespace\\{$name}Controller@index",   "permission" => "backoffice.$fullPath.list"]);
					$router->get("create",         ["as" => "backoffice.$fullPath.create",  "uses" => "$bkNamespace\\{$name}Controller@create",  "permission" => "backoffice.$fullPath.create"]);
					$router->post("/",             ["as" => "backoffice.$fullPath.store",   "uses" => "$bkNamespace\\{$name}Controller@store",   "permission" => "backoffice.$fullPath.create"]);
					$router->get("{{$path}}",      ["as" => "backoffice.$fullPath.show",    "uses" => "$bkNamespace\\{$name}Controller@show",    "permission" => "backoffice.$fullPath.read"]);
					$router->get("{{$path}}/edit", ["as" => "backoffice.$fullPath.edit",    "uses" => "$bkNamespace\\{$name}Controller@edit",    "permission" => "backoffice.$fullPath.update"]);
					$router->match(['PUT',
						  'PATCH'], "{{$path}}",   ["as" => "backoffice.$fullPath.update",  "uses" => "$bkNamespace\\{$name}Controller@update",  "permission" => "backoffice.$fullPath.update"]);
					$router->delete("{{$path}}",   ["as" => "backoffice.$fullPath.destroy", "uses" => "$bkNamespace\\{$name}Controller@destroy", "permission" => "backoffice.$fullPath.delete"]);
				});
			}

			$router->post('backoffice-users/{id}/resend-activation', ['as' => 'backoffice.backoffice-users.resend-activation', 'uses' => "Digbang\\L4Backoffice\\Auth\\UserController@resendActivation", 'permission' => 'backoffice.backoffice-users.update']);
			$router->post('backoffice-users/{id}/reset-password',    ['as' => 'backoffice.backoffice-users.reset-password',    'uses' => "Digbang\\L4Backoffice\\Auth\\UserController@resetPassword",    'permission' => 'backoffice.backoffice-users.update']);
		});
	}
}
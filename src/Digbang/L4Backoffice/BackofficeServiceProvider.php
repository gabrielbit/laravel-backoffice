<?php namespace Digbang\L4Backoffice;

use Cartalyst\Sentry\Users\ProviderInterface as UserProvider;
use Cartalyst\Sentry\Groups\ProviderInterface as GroupProvider;
use Cartalyst\Sentry\Throttling\ProviderInterface as ThrottleProvider;
use Digbang\FontAwesome\FontAwesomeServiceProvider;
use Digbang\Security\Filters\Auth;
use Digbang\Security\SecurityServiceProvider;
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
		/** @type Repository $config */
		$config = $this->app->make('config');

		/** @type Router $router */
		$router = $this->app->make(Router::class);

		$this->postRegister($config);

		$this->package('digbang/l4-backoffice');

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

		$this->app->singleton('menuFactory', Support\MenuFactory::class);
		$this->app->bind('linkMaker', Support\LinkMaker::class);
		$this->app->bind('Mustache_Engine', function(){
			return new \Mustache_Engine([
				'cache' => new \Mustache_Cache_FilesystemCache(storage_path('cache'))
			]);
		});
	}

	protected function postRegister(Repository $config)
	{
		$this->app->register(SecurityServiceProvider::class);

        if ($config->get('security::auth.driver') == 'custom')
        {
            if (! isset($this->app[UserProvider::class]))
            {
                $this->app->bind(UserProvider::class, Repositories\DoctrineUserRepository::class, true);
            }

            if (! isset($this->app[GroupProvider::class]))
            {
                $this->app->bind(GroupProvider::class, Repositories\DoctrineGroupRepository::class, true);
            }

            if (! isset($this->app[ThrottleProvider::class]))
            {
                $this->app->bind(ThrottleProvider::class, Repositories\DoctrineThrottleRepository::class, true);
            }
        }
	}

	protected function stringMacros(Str $str, Support\Str $myStr)
	{
		$str->macro('titleFromSlug', [$myStr, 'titleFromSlug']);
		$str->macro('parse', [$myStr, 'parse']);
	}

	protected function registerGenRoutes(Router $router)
	{
		$router->group(['prefix' => 'backoffice'], function() use ($router){
			$router->get( 'gen',           'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection');
			$router->post('gen/customize', 'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@customization');
			$router->post('gen/generate',  'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@generation');
			$router->get( 'gen/generate',  'Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@testGenerationPage');
		});
	}

	protected function registerAuthRoutes(Router $router)
	{
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
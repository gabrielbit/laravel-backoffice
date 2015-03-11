<?php namespace Digbang\L4Backoffice\Auth\Routes;

use Digbang\L4Backoffice\Auth\AuthController;
use GuiWoda\RouteBinder\RouteBinder;
use Illuminate\Routing\Router;

class AuthRouteBinder implements RouteBinder
{
	const LOGIN                   = 'backoffice.auth.login';
	const FORGOT_PASSWORD         = 'backoffice.auth.password.forgot';
	const RESET_PASSWORD          = 'backoffice.auth.password.reset';
	const LOGOUT                  = 'backoffice.auth.logout';
	const ACTIVATE                = 'backoffice.auth.activate';
	const AUTHENTICATE            = 'backoffice.auth.authenticate';
	const ATTEMPT_FORGOT_PASSWORD = 'backoffice.auth.password.forgot-request';
	const ATTEMPT_RESET_PASSWORD  = 'backoffice.auth.password.reset-request';

	/**
	 * Bind all needed routes to the router.
	 * You may also bind parameters, filters or anything you need to do
	 * with the router here.
	 *
	 * @param \Illuminate\Routing\Router $router
	 *
	 * @return void
	 */
	public function bind(Router $router)
	{
		$router->group(['prefix' => 'backoffice/auth'], function() use ($router){
			$router->get('login',                      ['as' => static::LOGIN,                   'uses' => AuthController::class . '@login']);
			$router->get('password/forgot',            ['as' => static::FORGOT_PASSWORD,         'uses' => AuthController::class . '@forgotPassword']);
			$router->get('password/reset/{id}/{code}', ['as' => static::RESET_PASSWORD,          'uses' => AuthController::class . '@resetPassword']);
			$router->get('logout',                     ['as' => static::LOGOUT,                  'uses' => AuthController::class . '@logout']);
			$router->get('activate/{code}',            ['as' => static::ACTIVATE,                'uses' => AuthController::class . '@activate']);
			$router->post('login',                     ['as' => static::AUTHENTICATE,            'uses' => AuthController::class . '@authenticate']);
			$router->post('password/forgot',           ['as' => static::ATTEMPT_FORGOT_PASSWORD, 'uses' => AuthController::class . '@forgotPasswordRequest']);
			$router->post('password/reset/{code}',     ['as' => static::ATTEMPT_RESET_PASSWORD,  'uses' => AuthController::class . '@resetPasswordRequest']);
		});
	}
}

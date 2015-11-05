<?php namespace Digbang\L4Backoffice\Auth\Routes;

use Digbang\L4Backoffice\Auth\Controllers\UserController;
use Digbang\Security\Entities\Permission;
use GuiWoda\RouteBinder\RouteBinder;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;

class UsersRouteBinder implements RouteBinder
{
	const EXPORT  = "backoffice.backoffice-users.export";
	const INDEX   = "backoffice.backoffice-users.index";
	const CREATE  = "backoffice.backoffice-users.create";
	const STORE   = "backoffice.backoffice-users.store";
	const SHOW    = "backoffice.backoffice-users.show";
	const EDIT    = "backoffice.backoffice-users.edit";
	const UPDATE  = "backoffice.backoffice-users.update";
	const DESTROY = "backoffice.backoffice-users.destroy";

	const RESEND_ACTIVATION = 'backoffice.backoffice-users.resend-activation';
	const RESET_PASSWORD = 'backoffice.backoffice-users.reset-password';

	/**
	 * @type Repository
	 */
	private $config;

	public function __construct(Repository $config)
	{
		$this->config = $config;
	}

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
		$prefix = $this->config->get('backoffice::auth.users_url', 'backoffice-users');

		$router->group(['prefix' => "backoffice/$prefix", 'before' => 'backoffice.auth.withPermissions|backoffice.urls.persistent'], function() use ($router) {
			$router->get("export",                  ['as' => static::EXPORT,  "uses" => UserController::class . '@export',  "permission" => Permission::USERS_LIST]);
			$router->get("/",                       ["as" => static::INDEX,   "uses" => UserController::class . '@index',   "permission" => Permission::USERS_LIST, "persistent" => true]);
			$router->get("create",                  ["as" => static::CREATE,  "uses" => UserController::class . '@create',  "permission" => Permission::USERS_CREATE]);
			$router->post("/",                      ["as" => static::STORE,   "uses" => UserController::class . '@store',   "permission" => Permission::USERS_CREATE]);
			$router->get("{backoffice_users}",      ["as" => static::SHOW,    "uses" => UserController::class . '@show',    "permission" => Permission::USERS_READ]);
			$router->get("{backoffice_users}/edit", ["as" => static::EDIT,    "uses" => UserController::class . '@edit',    "permission" => Permission::USERS_UPDATE]);
			$router->match(['PUT', 'PATCH'],
				"{backoffice_users}",               ["as" => static::UPDATE,  "uses" => UserController::class . '@update',  "permission" => Permission::USERS_UPDATE]);
			$router->delete("{backoffice_users}",   ["as" => static::DESTROY, "uses" => UserController::class . '@destroy', "permission" => Permission::USERS_DELETE]);

			$router->post('{backoffice_users}/resend-activation', ['as' => static::RESEND_ACTIVATION, 'uses' => UserController::class . '@resendActivation', 'permission' => Permission::USERS_UPDATE]);
			$router->post('{backoffice_users}/reset-password',    ['as' => static::RESET_PASSWORD,    'uses' => UserController::class . '@resetPassword',    'permission' => Permission::USERS_UPDATE]);
		});
	}
}

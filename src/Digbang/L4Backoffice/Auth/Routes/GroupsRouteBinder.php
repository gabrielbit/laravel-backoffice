<?php namespace Digbang\L4Backoffice\Auth\Routes;

use Digbang\L4Backoffice\Auth\GroupController;
use Digbang\L4Backoffice\Auth\Entities\Permission;
use GuiWoda\RouteBinder\RouteBinder;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;

class GroupsRouteBinder implements RouteBinder
{
	const EXPORT  = "backoffice.backoffice-groups.export";
	const INDEX   = "backoffice.backoffice-groups.index";
	const CREATE  = "backoffice.backoffice-groups.create";
	const STORE   = "backoffice.backoffice-groups.store";
	const SHOW    = "backoffice.backoffice-groups.show";
	const EDIT    = "backoffice.backoffice-groups.edit";
	const UPDATE  = "backoffice.backoffice-groups.update";
	const DESTROY = "backoffice.backoffice-groups.destroy";

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
		$prefix = $this->config->get('l4-backoffice::auth.groups_url', 'backoffice-groups');

		$router->group(['prefix' => "backoffice/$prefix", 'before' => 'backoffice.auth.withPermissions'], function() use ($router) {
			$router->get("export",                   ['as' => static::EXPORT,  "uses" => GroupController::class . '@export',  "permission" => Permission::GROUP_LIST]);
			$router->get("/",                        ["as" => static::INDEX,   "uses" => GroupController::class . '@index',   "permission" => Permission::GROUP_LIST]);
			$router->get("create",                   ["as" => static::CREATE,  "uses" => GroupController::class . '@create',  "permission" => Permission::GROUP_CREATE]);
			$router->post("/",                       ["as" => static::STORE,   "uses" => GroupController::class . '@store',   "permission" => Permission::GROUP_CREATE]);
			$router->get("{backoffice_groups}",      ["as" => static::SHOW,    "uses" => GroupController::class . '@show',    "permission" => Permission::GROUP_READ]);
			$router->get("{backoffice_groups}/edit", ["as" => static::EDIT,    "uses" => GroupController::class . '@edit',    "permission" => Permission::GROUP_UPDATE]);
			$router->match(['PUT', 'PATCH'],
				"{backoffice_groups}",               ["as" => static::UPDATE,  "uses" => GroupController::class . '@update',  "permission" => Permission::GROUP_UPDATE]);
			$router->delete("{backoffice_groups}",   ["as" => static::DESTROY, "uses" => GroupController::class . '@destroy', "permission" => Permission::GROUP_DELETE]);
		});
	}
}

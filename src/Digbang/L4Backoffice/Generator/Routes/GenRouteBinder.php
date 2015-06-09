<?php namespace Digbang\L4Backoffice\Generator\Routes;

use Digbang\L4Backoffice\Generator\Controllers\GenController;
use GuiWoda\RouteBinder\RouteBinder;
use Illuminate\Routing\Router;

class GenRouteBinder implements RouteBinder
{
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
		$router->group(['prefix' => 'backoffice'], function() use ($router){
			$router->get( 'gen',           GenController::class . '@modelSelection');
			$router->post('gen/customize', GenController::class . '@customization');
			$router->post('gen/generate',  GenController::class . '@generation');
			$router->get( 'gen/generate',  GenController::class . '@testGenerationPage');
		});
	}
}

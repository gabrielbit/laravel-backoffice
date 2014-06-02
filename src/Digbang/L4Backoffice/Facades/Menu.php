<?php namespace Digbang\L4Backoffice\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Menu
 * @package Digbang\L4Backoffice\Facades
 */
class Menu extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'menuFactory';
	}

} 
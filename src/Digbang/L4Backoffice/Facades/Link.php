<?php namespace Digbang\L4Backoffice\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Link
 * @package Digbang\L4Backoffice\Facades
 */
class Link extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'linkMaker';
	}

} 
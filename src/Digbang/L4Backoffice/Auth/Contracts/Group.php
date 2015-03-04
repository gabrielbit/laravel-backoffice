<?php namespace Digbang\L4Backoffice\Auth\Contracts;

use Cartalyst\Sentry\Groups\GroupInterface;

interface Group extends GroupInterface
{
	/**
	 * @param string $name
	 * @param array  $permissions
	 *
	 * @return Group
	 */
	public static function create($name, array $permissions = []);
}

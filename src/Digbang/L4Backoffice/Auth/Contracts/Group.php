<?php namespace Digbang\L4Backoffice\Auth\Contracts;

interface Group
{
	/**
	 * @param string $name
	 * @param array  $permissions
	 *
	 * @return Group
	 */
	public static function create($name, array $permissions = []);
}

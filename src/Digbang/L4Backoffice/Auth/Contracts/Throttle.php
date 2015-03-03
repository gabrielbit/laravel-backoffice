<?php namespace Digbang\L4Backoffice\Auth\Contracts;

interface Throttle
{
	/**
	 * @param User   $user
	 * @param string $ipAddress
	 *
	 * @return Throttle
	 */
	public static function create(User $user, $ipAddress);
}

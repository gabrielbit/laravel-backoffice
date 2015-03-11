<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\Group as GroupInterface;

trait GroupPermissionTrait
{
	/**
	 * @type GroupInterface
	 */
	private $group;

	/**
	 * @return bool
	 */
	public function isAllowed()
	{
		return true;
	}
}

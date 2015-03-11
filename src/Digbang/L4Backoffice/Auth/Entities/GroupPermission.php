<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts;

class GroupPermission extends Permission implements Contracts\Permission
{
	use GroupPermissionTrait;

	/**
	 * @param Contracts\Group $group
	 * @param Permission      $permission
	 */
	public function __construct(Contracts\Group $group, $permission)
	{
		parent::__construct($permission);

		$this->group = $group;
	}
}

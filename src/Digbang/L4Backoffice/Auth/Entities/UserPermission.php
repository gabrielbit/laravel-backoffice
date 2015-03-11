<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts;

class UserPermission extends Permission implements Contracts\Permission
{
	use UserPermissionTrait;

	/**
	 * @param Contracts\User $user
	 * @param string         $permission
	 * @param bool           $allowed
	 */
	public function __construct(Contracts\User $user, $permission, $allowed = true)
	{
		parent::__construct($permission);

		$this->user = $user;
		$this->allowed = $allowed;
	}

	/**
	 * @return void
	 */
	public function deny()
	{
		$this->allowed = false;
	}
}

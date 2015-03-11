<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\User as UserInterface;

trait UserPermissionTrait
{
	/**
	 * @type UserInterface
	 */
	protected $user;

	/**
	 * @type bool
	 */
	protected $allowed = false;

	/**
	 * @return bool
	 */
	public function isAllowed()
	{
		return $this->allowed;
	}
}

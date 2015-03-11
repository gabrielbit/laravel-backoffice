<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\Permission as PermissionInterface;

abstract class Permission implements PermissionInterface
{
	const USERS_LIST   = 'backoffice.backoffice-users.list';
	const USERS_CREATE = 'backoffice.backoffice-users.create';
	const USERS_READ   = 'backoffice.backoffice-users.read';
	const USERS_UPDATE = 'backoffice.backoffice-users.update';
	const USERS_DELETE = 'backoffice.backoffice-users.delete';

	const GROUP_LIST   = 'backoffice.backoffice-groups.list';
	const GROUP_CREATE = 'backoffice.backoffice-groups.create';
	const GROUP_READ   = 'backoffice.backoffice-groups.read';
	const GROUP_UPDATE = 'backoffice.backoffice-groups.update';
	const GROUP_DELETE = 'backoffice.backoffice-groups.delete';

	use PermissionTrait {
		allows as evaluatePermission;
	}

	protected static $permissions = [];

	public function __construct($permission)
	{
		$this->validate($permission);

		$this->permission = $permission;
	}

	abstract public function isAllowed();

	public function allows($aPermission)
	{
		if (! $this->isAllowed())
		{
			return false;
		}

		return $this->evaluatePermission($aPermission);
	}

	public function validate($permission)
	{
		static::makePermissions();

		if (! in_array($permission, static::$permissions))
		{
			throw new \UnexpectedValueException("Permission $permission does not exist.");
		}
	}

	protected static function makePermissions()
	{
		if (empty(static::$permissions))
		{
			static::$permissions = (new \ReflectionClass(static::class))->getConstants();
		}
	}
}

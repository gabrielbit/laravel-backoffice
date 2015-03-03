<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\Group as GroupInterface;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Doctrine\Common\Collections\ArrayCollection;

final class Group implements GroupInterface, RepositoryAware
{
	use GroupTrait;

	/**
	 * @param string $name
	 * @param array  $permissions
	 */
	public function __construct($name, array $permissions = [])
	{
		$this->name        = $name;
		$this->permissions = new ArrayCollection($permissions);
	}

	/**
	 * @param string $name
	 * @param array  $permissions
	 *
	 * @return GroupInterface
	 */
	public static function create($name, array $permissions = [])
	{
		return new static($name, $permissions);
	}
}

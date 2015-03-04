<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Carbon\Carbon;
use Digbang\L4Backoffice\Auth\Contracts\User as UserInterface;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Doctrine\Common\Collections\ArrayCollection;

final class User implements UserInterface, RepositoryAware
{
	use UserTrait;

	/**
	 * @param string $email
	 * @param string $password
	 */
	public function __construct($email, $password)
	{
		$this->email    = $email;
		$this->password = $password;
	}

	/**
	 * @param string $email
	 * @param string $password
	 *
	 * @return User
	 */
	public static function createFromCredentials($email, $password)
	{
		return new static($email, $password);
	}

	/**
	 * @param string $firstName
	 * @param string $lastName
	 *
	 * @return void
	 */
	public function named($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	/**
	 * @return void
	 */
	public function forceActivation()
	{
		$this->activated = true;
		$this->activatedAt = new Carbon;
		$this->activationCode = null;
	}

	/**
	 * @param $permissions
	 *
	 * @return void
	 */
	public function setAllPermissions($permissions)
	{
		$this->permissions = new ArrayCollection($permissions);
	}
}

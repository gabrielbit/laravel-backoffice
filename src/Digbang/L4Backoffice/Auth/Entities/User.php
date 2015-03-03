<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\User as UserInterface;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;

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
}

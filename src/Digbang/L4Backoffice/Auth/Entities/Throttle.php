<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Digbang\L4Backoffice\Auth\Contracts\User as UserInterface;
use Digbang\L4Backoffice\Auth\Contracts\Throttle as ThrottleInterface;

final class Throttle implements ThrottleInterface, RepositoryAware
{
	use ThrottleTrait;

	/**
	 * @param User   $user
	 * @param string $ipAddress
	 */
	public function __construct(User $user, $ipAddress)
	{
		$this->user = $user;
		$this->ipAddress = $ipAddress;
	}

	/**
	 * @param UserInterface $user
	 * @param string        $ipAddress
	 *
	 * @return ThrottleInterface
	 */
	public static function create(UserInterface $user, $ipAddress)
	{
		return new static($user, $ipAddress);
	}
}

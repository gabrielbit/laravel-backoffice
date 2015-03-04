<?php namespace Digbang\L4Backoffice\Auth\Services;

use Cartalyst\Sentry\Users\ProviderInterface as UserProvider;
use Digbang\L4Backoffice\Auth\Contracts\User;

class UserService
{
	/**
	 * @type UserProvider
	 */
	private $userProvider;

	/**
	 * @param \Cartalyst\Sentry\Users\ProviderInterface $userProvider
	 */
	public function __construct(UserProvider $userProvider)
	{
		$this->userProvider = $userProvider;
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
	 * @param bool $activated
	 *
	 * @return \Digbang\L4Backoffice\Auth\Contracts\User
	 */
	public function create($email, $password, $firstName = null, $lastName = null, $activated = false)
	{
		/** @type \Digbang\L4Backoffice\Auth\Contracts\User $user */
		$user = $this->userProvider->create(compact('email', 'password'));

		$user->named($firstName, $lastName);

		if ($activated)
		{
			$user->forceActivation();
		}

		$user->save();

		return $user;
	}

	/**
	 * @param $id
	 *
	 * @return \Digbang\L4Backoffice\Auth\Contracts\User
	 */
	public function find($id)
	{
		return $this->userProvider->findById($id);
	}

	public function edit(User $user, $firstName, $lastName, $email, $password, $groups = [], $permissions = [])
	{
		$user->named($firstName, $lastName);

		$user->changeEmail($email);
		$user->changePassword($password);

		$user->setAllGroups($groups);
		$user->setAllPermissions($permissions);

		$user->save();

		return $user;
	}

	public function delete($id)
	{
		$user = $this->userProvider->findById($id);

		$user->delete();
	}
}

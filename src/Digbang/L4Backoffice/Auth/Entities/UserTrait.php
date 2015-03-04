<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Cartalyst\Sentry\Users\UserExistsException;
use Digbang\Doctrine\TimestampsTrait;
use Digbang\L4Backoffice\Repositories\DoctrineUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;

trait UserTrait
{
	use TimestampsTrait;

	/**
	 * @type int
	 */
	private $id;

	/**
	 * @type string
	 */
	private $email;

	/**
	 * @type string
	 */
	private $password;

	/**
	 * @type string
	 */
	private $firstName;

	/**
	 * @type string
	 */
	private $lastName;

	/**
	 * @type \DateTimeInterface
	 */
	private $lastLogin;

	/**
	 * @type bool
	 */
	private $activated = false;

	/**
	 * @type string
	 */
	private $activationCode;

	/**
	 * @type \DateTimeInterface
	 */
	private $activatedAt;

	/**
	 * @type bool
	 */
	private $isSuperUser = false;

	/**
	 * @type string
	 */
	private $persistCode;

	/**
	 * @type string
	 */
	private $resetPasswordCode;

	/**
	 * @type ArrayCollection
	 */
	private $groups;

	/**
	 * @type ArrayCollection
	 */
	private $permissions;

	/**
	 * @type array
	 */
	private $mergedPermissions;

	/**
	 * @param DoctrineUserRepository $userRepository
	 */
	public function setRepository(ObjectRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * This is needed to emulate an AR behavior.
	 * Sentry uses AR to save/delete entities...
	 *
	 * @type DoctrineUserRepository
	 */
	private $userRepository;

	/**
	 * Returns the user's ID.
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the name for the user's login.
	 *
	 * @return string
	 */
	public function getLoginName()
	{
		return 'email';
	}

	/**
	 * Returns the user's login.
	 *
	 * @return string
	 */
	public function getLogin()
	{
		return $this->email;
	}

	/**
	 * Returns the name for the user's password.
	 *
	 * @return string
	 */
	public function getPasswordName()
	{
		return 'password';
	}

	/**
	 * Returns the user's password (hashed).
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Returns permissions for the user.
	 *
	 * @return array
	 */
	public function getPermissions()
	{
		return $this->permissions->toArray();
	}

	/**
	 * Check if the user is activated.
	 *
	 * @return bool
	 */
	public function isActivated()
	{
		return $this->activated;
	}

	/**
	 * Checks if the user is a super user - has
	 * access to everything regardless of permissions.
	 *
	 * @return bool
	 */
	public function isSuperUser()
	{
		return $this->isSuperUser;
	}

	/**
	 * Validates the user && throws a number of
	 * Exceptions if validation fails.
	 *
	 * @return bool
	 * @throws \Cartalyst\Sentry\Users\LoginRequiredException
	 * @throws \Cartalyst\Sentry\Users\UserExistsException
	 */
	public function validate()
	{
		if (! $this->email)
		{
			throw new LoginRequiredException("A login is required for a user, none given.");
		}

		if (! $this->getPassword())
		{
			throw new PasswordRequiredException("A password is required for user [$this->email], none given.");
		}

		// Check if the user already exists
		$persistedUser = $this->userRepository->findOneBy(['email' => $this->email]);

		if ($persistedUser && $persistedUser->getId() != $this->getId())
		{
			throw new UserExistsException(
				"A user already exists with login [$this->email], logins must be unique for users."
			);
		}

		return true;
	}

	/**
	 * Save the user.
	 *
	 * @return bool
	 */
	public function save()
	{
		$this->userRepository->save($this);

		return true;
	}

	/**
	 * Delete the user.
	 *
	 * @return bool
	 */
	public function delete()
	{
		$this->userRepository->delete($this);

		return true;
	}

	/**
	 * Gets a code for when the user is
	 * persisted to a cookie or session which
	 * identifies the user.
	 *
	 * @return string
	 */
	public function getPersistCode()
	{
		return $this->persistCode;
	}

	/**
	 * Checks the given persist code.
	 *
	 * @param  string $persistCode
	 *
	 * @return bool
	 */
	public function checkPersistCode($persistCode)
	{
		return $this->persistCode == $persistCode;
	}

	/**
	 * Get an activation code for the given user.
	 *
	 * @return string
	 */
	public function getActivationCode()
	{
		$this->activationCode = $activationCode = uniqid('', true);

		$this->save();

		return $activationCode;
	}

	/**
	 * Attempts to activate the given user by checking
	 * the activate code. If the user is activated already,
	 * an Exception is thrown.
	 *
	 * @param  string $activationCode
	 *
	 * @return bool
	 * @throws \Cartalyst\Sentry\Users\UserAlreadyActivatedException
	 */
	public function attemptActivation($activationCode)
	{
		if ($this->isActivated())
		{
			throw new UserAlreadyActivatedException;
		}

		if ($this->activationCode != $activationCode)
		{
			return false;
		}

		$this->activationCode = null;
		$this->activated    = true;
		$this->activatedAt    = new \DateTimeImmutable;

		return $this->save();
	}

	/**
	 * Checks the password passed matches the user's password.
	 *
	 * @param  string $password
	 *
	 * @return bool
	 */
	public function checkPassword($password)
	{
		return $this->userRepository->checkHash($this->getPassword(), $password);
	}

	/**
	 * Get a reset password code for the given user.
	 *
	 * @return string
	 */
	public function getResetPasswordCode()
	{
		$this->resetPasswordCode = $resetCode = uniqid('', true);

		$this->save();

		return $resetCode;
	}

	/**
	 * Checks if the provided user reset password code is
	 * valid without actually resetting the password.
	 *
	 * @param  string $resetCode
	 *
	 * @return bool
	 */
	public function checkResetPasswordCode($resetCode)
	{
		return $this->resetPasswordCode == $resetCode;
	}

	/**
	 * Attempts to reset a user's password by matching
	 * the reset code generated with the user's.
	 *
	 * @param  string $resetCode
	 * @param  string $newPassword
	 *
	 * @return bool
	 */
	public function attemptResetPassword($resetCode, $newPassword)
	{
		if ($this->checkResetPasswordCode($resetCode))
		{
			$this->password          = $newPassword;
			$this->resetPasswordCode = null;

			return $this->save();
		}

		return false;
	}

	/**
	 * Wipes out the data associated with resetting
	 * a password.
	 *
	 * @return void
	 */
	public function clearResetPassword()
	{
		if ($this->resetPasswordCode)
		{
			$this->resetPasswordCode = null;
			$this->save();
		}
	}

	/**
	 * Returns an array of groups which the given
	 * user belongs to.
	 *
	 * @return array
	 */
	public function getGroups()
	{
		return $this->groups->toArray();
	}

	/**
	 * Adds the user to the given group
	 *
	 * @param  \Cartalyst\Sentry\Groups\GroupInterface $group
	 *
	 * @return bool
	 */
	public function addGroup(GroupInterface $group)
	{
		return $this->groups->add($group);
	}

	/**
	 * Removes the user from the given group.
	 *
	 * @param  \Cartalyst\Sentry\Groups\GroupInterface $group
	 *
	 * @return bool
	 */
	public function removeGroup(GroupInterface $group)
	{
		return $this->groups->removeElement($group);
	}

	/**
	 * See if the user is in the given group.
	 *
	 * @param  \Cartalyst\Sentry\Groups\GroupInterface $group
	 *
	 * @return bool
	 */
	public function inGroup(GroupInterface $group)
	{
		return $this->groups->contains($group);
	}

	/**
	 * Returns an array of merged permissions for each
	 * group the user is in.
	 *
	 * @return array
	 */
	public function getMergedPermissions()
	{
		if (! $this->mergedPermissions)
		{
			$permissions = [];

			foreach ($this->getGroups() as $group)
			{
				/** @type GroupInterface $group */
				$permissions = array_merge($permissions, $group->getPermissions());
			}

			$this->mergedPermissions = array_merge($permissions, $this->getPermissions());
		}

		return $this->mergedPermissions;
	}

	/**
	 * See if a user has access to the passed permission(s).
	 * Permissions are merged from all groups the user belongs to
	 * && then are checked against the passed permission(s).
	 *
	 * If multiple permissions are passed, the user must
	 * have access to all permissions passed through, unless the
	 * "all" flag is set to false.
	 *
	 * Super users have access no matter what.
	 *
	 * @param  string|array $permissions
	 * @param  bool         $all
	 *
	 * @return bool
	 */
	public function hasAccess($permissions, $all = true)
	{
		if ($this->isSuperUser())
		{
			return true;
		}

		return $this->hasPermission($permissions, $all);
	}

	/**
	 * See if a user has access to the passed permission(s).
	 * Permissions are merged from all groups the user belongs to
	 * && then are checked against the passed permission(s).
	 *
	 * If multiple permissions are passed, the user must
	 * have access to all permissions passed through, unless the
	 * "all" flag is set to false.
	 *
	 * Super users DON'T have access no matter what.
	 *
	 * @param  string|array $permissions
	 * @param  bool         $all
	 *
	 * @return bool
	 */
	public function hasPermission($permissions, $all = true)
	{
		$mergedPermissions = $this->getMergedPermissions();

		if (! is_array($permissions))
		{
			$permissions = (array) $permissions;
		}

		foreach ($permissions as $permission)
		{
			// We will set a flag now for whether this permission was
			// matched at all.
			$matched = true;

			// Now, let's check if the permission ends in a wildcard "*" symbol.
			// If it does, we'll check through all the merged permissions to see
			// if a permission exists which matches the wildcard.
			if ((strlen($permission) > 1) && ends_with($permission, '*'))
			{
				$matched = false;

				foreach ($mergedPermissions as $mergedPermission => $value)
				{
					// Strip the '*' off the end of the permission.
					$checkPermission = substr($permission, 0, -1);

					// We will make sure that the merged permission does not
					// exactly match our permission, but starts with it.
					if ($checkPermission != $mergedPermission &&
						starts_with($mergedPermission, $checkPermission) &&
						$value == 1
					)
					{
						$matched = true;
						break;
					}
				}
			}

			elseif ((strlen($permission) > 1) && starts_with($permission, '*'))
			{
				$matched = false;

				foreach ($mergedPermissions as $mergedPermission => $value)
				{
					// Strip the '*' off the beginning of the permission.
					$checkPermission = substr($permission, 1);

					// We will make sure that the merged permission does not
					// exactly match our permission, but ends with it.
					if ($checkPermission != $mergedPermission &&
						ends_with($mergedPermission, $checkPermission) &&
						$value == 1
					)
					{
						$matched = true;
						break;
					}
				}
			}

			else
			{
				$matched = false;

				foreach ($mergedPermissions as $mergedPermission => $value)
				{
					// This time check if the mergedPermission ends in wildcard "*" symbol.
					if ((strlen($mergedPermission) > 1) && ends_with($mergedPermission, '*'))
					{
						$matched = false;

						// Strip the '*' off the end of the permission.
						$checkMergedPermission = substr($mergedPermission, 0, -1);

						// We will make sure that the merged permission does not
						// exactly match our permission, but starts with it.
						if ($checkMergedPermission != $permission &&
							starts_with($permission, $checkMergedPermission) &&
							$value == 1
						)
						{
							$matched = true;
							break;
						}
					}

					// Otherwise, we'll fallback to standard permissions checking where
					// we match that permissions explicitly exist.
					elseif ($permission == $mergedPermission && $mergedPermissions[$permission] == 1)
					{
						$matched = true;
						break;
					}
				}
			}

			// Now, we will check if we have to match all
			// permissions or any permission && return
			// accordingly.
			if ($all === true && $matched === false)
			{
				return false;
			}
			elseif ($all === false && $matched === true)
			{
				return true;
			}
		}

		if ($all === false)
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns if the user has access to any of the
	 * given permissions.
	 *
	 * @param  array $permissions
	 *
	 * @return bool
	 */
	public function hasAnyAccess(array $permissions)
	{
		return $this->hasAccess($permissions, false);
	}

	/**
	 * Records a login for the user.
	 *
	 * @return void
	 */
	public function recordLogin()
	{
		$this->lastLogin = new \DateTimeImmutable;

		$this->save();
	}

	/**
	 * @param string $firstName
	 * @param string $lastName
	 */
	public function setName($firstName, $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName  = $lastName;
	}

	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param string $newPassword (hashed)
	 */
	public function changePassword($newPassword)
	{
		$this->password = $newPassword;
	}
}

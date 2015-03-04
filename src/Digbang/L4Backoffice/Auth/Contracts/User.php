<?php namespace Digbang\L4Backoffice\Auth\Contracts;

use Cartalyst\Sentry\Users\UserInterface;

interface User extends UserInterface
{
	/**
	 * @param string $email
	 * @param string $password
	 *
	 * @return User
	 */
	public static function createFromCredentials($email, $password);

	/**
	 * @param string $newPassword The hashed password
	 *
	 * @return void
	 */
	public function changePassword($newPassword);
}

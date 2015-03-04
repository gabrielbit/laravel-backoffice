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

	/**
	 * @param string $firstName
	 * @param string $lastName
	 *
	 * @return void
	 */
	public function named($firstName, $lastName);

	/**
	 * @return void
	 */
	public function forceActivation();

	/**
	 * @param $permissions
	 *
	 * @return void
	 */
	public function setAllPermissions($permissions);

	/**
	 * @return string
	 */
	public function getFirstName();

	/**
	 * @return string
	 */
	public function getLastName();

	/**
	 * @return string
	 */
	public function getEmail();

	/**
	 * @return \Carbon\Carbon
	 */
	public function getActivatedAt();

	/**
	 * @return \Carbon\Carbon
	 */
	public function getLastLogin();

	/**
	 * @return array
	 */
	public function getAllPermissions();

	/**
	 * @param string $email
	 *
	 * @return void
	 */
	public function changeEmail($email);

	/**
	 * @param array $groups
	 *
	 * @return void
	 */
	public function setAllGroups($groups);
}

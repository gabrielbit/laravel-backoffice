<?php namespace Digbang\L4Backoffice\Auth\Contracts;

interface Permission
{
	/**
	 * @param $aPermission
	 *
	 * @return bool
	 */
	public function allows($aPermission);

	/**
	 * @return bool
	 */
	public function isAllowed();
}

<?php namespace Digbang\L4Backoffice\Auth\Services;

use Cartalyst\Sentry\Groups\ProviderInterface as GroupProvider;

class GroupService
{
	/**
	 * @type GroupProvider
	 */
	private $groupProvider;

	public function __construct(GroupProvider $groupProvider)
	{
		$this->groupProvider = $groupProvider;
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->groupProvider->findAll();
	}
}

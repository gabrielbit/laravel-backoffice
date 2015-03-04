<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Digbang\Doctrine\TimestampsTrait;
use Digbang\L4Backoffice\Repositories\DoctrineGroupRepository;
use Digbang\L4Backoffice\Support\MagicPropertyTrait;
use Doctrine\Common\Persistence\ObjectRepository;

trait GroupTrait
{
	use TimestampsTrait;
	use MagicPropertyTrait;

	/**
	 * @type int
	 */
	private $id;

	/**
	 * @type string
	 */
	private $name;

	/**
	 * @type \Doctrine\Common\Collections\ArrayCollection
	 */
	private $permissions;

	/**
	 * @type DoctrineGroupRepository
	 */
	private $groupRepository;

	/**
	 * Returns the group's ID.
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the group's name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns permissions for the group.
	 *
	 * @return array
	 */
	public function getPermissions()
	{
		return $this->permissions->toArray();
	}

	/**
	 * Saves the group.
	 *
	 * @return bool
	 */
	public function save()
	{
		$this->groupRepository->save($this);

		return true;
	}

	/**
	 * Delete the group.
	 *
	 * @return bool
	 */
	public function delete()
	{
		$this->groupRepository->delete($this);

		return true;
	}

	/**
	 * @return \Carbon\Carbon
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * @return \Carbon\Carbon
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	/**
	 * @param DoctrineGroupRepository $groupRepository
	 */
	public function setRepository(ObjectRepository $groupRepository)
	{
		$this->groupRepository = $groupRepository;
	}
}
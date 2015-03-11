<?php namespace Digbang\L4Backoffice\Auth\Services;

use Digbang\L4Backoffice\Auth\Contracts\Group;
use Digbang\L4Backoffice\Repositories\DoctrineGroupRepository;
use Doctrine\Common\Collections\Criteria;

class GroupService
{
	/**
	 * @type DoctrineGroupRepository
	 */
	private $groupRepository;

	/**
	 * @param DoctrineGroupRepository $groupRepository
	 */
	public function __construct(DoctrineGroupRepository $groupRepository)
	{
		$this->groupRepository = $groupRepository;
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->groupRepository->findAll();
	}

	/**
	 * @param int $id
	 *
	 * @return \Digbang\L4Backoffice\Auth\Contracts\Group
	 */
	public function find($id)
	{
		return $this->groupRepository->findById($id);
	}

	public function findAll(array $ids)
	{
		return array_map([$this, 'find'], $ids);
	}

	/**
	 * @param string $name
	 * @param array  $permissions
	 *
	 * @return \Digbang\L4Backoffice\Auth\Contracts\Group
	 */
	public function create($name, array $permissions = [])
	{
		return $this->groupRepository->create([
			'name' => $name,
			'permissions' => $permissions
		]);
	}

	/**
	 * @param Group  $group
	 * @param string $name
	 * @param array  $permissions
	 */
	public function edit(Group $group, $name, array $permissions)
	{
		$group->changeName($name);
		$group->setPermissions($permissions);

		$this->groupRepository->save($group);
	}

	public function delete($id)
	{
		$group = $this->groupRepository->find($id);

		$this->groupRepository->delete($group);
	}

	public function search($name = null, $permission = null, $orderBy = null, $orderSense = 'asc', $limit = 10, $offset = 0)
	{
		return $this->groupRepository->search($name, $permission, $orderBy, $orderSense, $limit, $offset);
	}
}

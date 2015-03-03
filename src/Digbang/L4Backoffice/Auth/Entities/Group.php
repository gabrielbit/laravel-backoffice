<?php namespace Digbang\L4Backoffice\Auth\Entities;

use Cartalyst\Sentry\Groups\GroupInterface;
use Digbang\Doctrine\TimestampsTrait;
use Digbang\L4Backoffice\Repositories\DoctrineGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;

class Group implements GroupInterface
{
    use TimestampsTrait;

    /**
     * @type int
     */
    private $id;

    /**
     * @type string
     */
    private $name;

    /**
     * @type ArrayCollection
     */
    private $permissions;

    /**
     * @type DoctrineGroupRepository
     */
    private $groupRepository;

    /**
     * @param string $name
     * @param array  $permissions
     */
    public function __construct($name, array $permissions = [])
    {
        $this->name        = $name;
        $this->permissions = new ArrayCollection($permissions);
    }

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
     * @param DoctrineGroupRepository $groupRepository
     */
    public function setGroupRepository(DoctrineGroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }
}

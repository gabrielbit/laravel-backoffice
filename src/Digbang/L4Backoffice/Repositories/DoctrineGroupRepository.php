<?php namespace Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\ProviderInterface as GroupProviderInterface;
use Digbang\L4Backoffice\Auth\Entities\Group;
use Digbang\L4Backoffice\Support\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class DoctrineGroupRepository extends EntityRepository implements GroupProviderInterface
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Group::class));
    }

    /**
     * Find the group by ID.
     *
     * @param  int $id
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface  $group
     * @throws \Cartalyst\Sentry\Groups\GroupNotFoundException
     */
    public function findById($id)
    {
        $group = $this->find($id);

        if (!$group)
        {
            throw new GroupNotFoundException("Group $id not found.");
        }

        return $this->group($group);
    }

    /**
     * Find the group by name.
     *
     * @param  string $name
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface  $group
     * @throws \Cartalyst\Sentry\Groups\GroupNotFoundException
     */
    public function findByName($name)
    {
        $group = $this->findOneBy([
            'name' => $name
        ]);

        if (!$group)
        {
            throw new GroupNotFoundException("Group $name not found.");
        }

        return $this->group($group);
    }

    /**
     * Creates a group.
     *
     * @param  array $attributes
     *
     * @return \Cartalyst\Sentry\Groups\GroupInterface
     */
    public function create(array $attributes)
    {
        $group = new Group($attributes['name'], array_get($attributes, 'permissions', []));

        $this->save($group);

        return $this->group($group);
    }

    /**
     * @param GroupInterface $group
     */
    public function save(GroupInterface $group)
    {
        $em = $this->getEntityManager();

        $em->persist($group);
        $em->flush($group);
    }

    /**
     * @param GroupInterface $group
     */
    public function delete(GroupInterface $group)
    {
        $em = $this->getEntityManager();

        $em->remove($group);
        $em->flush();
    }

    /**
     * @param Group $group
     *
     * @return Group
     */
    private function group(Group $group)
    {
        $group->setGroupRepository($this);

        return $group;
    }
}

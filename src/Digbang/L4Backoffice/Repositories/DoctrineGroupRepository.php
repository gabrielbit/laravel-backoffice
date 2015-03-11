<?php namespace Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\ProviderInterface as GroupProviderInterface;
use Digbang\L4Backoffice\Auth\Contracts\Group as GroupInterface;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Digbang\L4Backoffice\Auth\Entities\Group;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Illuminate\Config\Repository;

class DoctrineGroupRepository extends EntityRepository implements GroupProviderInterface
{
	private $entityName;

	/**
	 * @param EntityManagerInterface        $em
	 * @param \Illuminate\Config\Repository $config
	 */
    public function __construct(EntityManagerInterface $em, Repository $config)
    {
        parent::__construct(
	        $em,
	        $em->getClassMetadata(
		        $this->entityName = $config->get('security::auth.groups.model', Group::class)
	        )
        );
    }

    /**
     * Find the group by ID.
     *
     * @param  int $id
     *
     * @return GroupInterface $group
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
     * @return GroupInterface  $group
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
     * @return GroupInterface
     */
    public function create(array $attributes)
    {
	    $entityName = $this->entityName;

        $group = $entityName::create($attributes['name'], array_get($attributes, 'permissions', []));

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
        $em->flush();
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
     * @param GroupInterface $group
     *
     * @return GroupInterface
     */
    private function group(GroupInterface $group)
    {
	    if ($group instanceof RepositoryAware)
	    {
		    $group->setRepository($this);
	    }

        return $group;
    }

	public function search($name = null, $permission = null, $orderBy = null, $orderSense = 'asc', $limit = 10, $offset = 0)
	{
		$queryBuilder = $this->createQueryBuilder('g');
		$expressionBuilder = Criteria::expr();

		$filters = [];

		if ($name)
		{
			$filters[] = $expressionBuilder->contains('name', $name);
		}

		$criteria = Criteria::create();

		if (!empty($filters))
		{
			$criteria->where($expressionBuilder->andX(...$filters));
		}

		if ($orderBy && $orderSense)
		{
			$criteria->orderBy([$orderBy => $orderSense]);
		}

		$criteria->setMaxResults($limit);
		$criteria->setFirstResult($offset);

		$queryBuilder->addCriteria($criteria);

		if ($permission !== null)
		{
			$permissionClass = $this->getClassMetadata()->getAssociationMapping('permissions')['targetEntity'];
			$queryBuilder->andWhere($queryBuilder->expr()->exists(
				"SELECT 1 FROM $permissionClass p WHERE p.permission LIKE :permission AND p.group = g.id"
			));

			$queryBuilder->setParameter('permission', "%$permission%");
		}

		return $queryBuilder->getQuery()->getResult();
	}
}

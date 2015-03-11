<?php namespace Digbang\L4Backoffice\Auth\Services;

use Digbang\L4Backoffice\Auth\Contracts\User;
use Digbang\L4Backoffice\Repositories\DoctrineUserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
	/**
	 * @type DoctrineUserRepository
	 */
	private $userRepository;

	/**
	 * @type EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @type GroupService
	 */
	private $groupService;

	public function __construct(DoctrineUserRepository $userRepository, EntityManagerInterface $entityManager, GroupService $groupService)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
		$this->groupService = $groupService;
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
	 * @param bool   $activated
	 * @param array  $groups
	 * @param array  $permissions
	 * @param bool   $superUser
	 *
	 * @return User
	 * @throws \Exception
	 */
	public function create($email, $password, $firstName = null, $lastName = null, $activated = false, array $groups = [], array $permissions = [], $superUser = false)
	{
		$this->entityManager->beginTransaction();

		try
		{
			/** @type \Digbang\L4Backoffice\Auth\Contracts\User $user */
			$user = $this->userRepository->create(compact('email', 'password'));

			$user->named($firstName, $lastName);

			if ($activated)
			{
				$user->forceActivation();
			}

			if ($superUser)
			{
				$user->promoteToSuperUser();
			}

			$user->setAllGroups($this->groupService->findAll($groups));
			$user->setAllPermissions($permissions);

			$user->save();

			$this->entityManager->commit();

			return $user;
		}
		catch (\Exception $e)
		{
			$this->entityManager->rollback();

			throw $e;
		}
	}

	/**
	 * @param $id
	 *
	 * @return \Digbang\L4Backoffice\Auth\Contracts\User
	 */
	public function find($id)
	{
		return $this->userRepository->findById($id);
	}

	public function edit(User $user, $firstName, $lastName, $email, $password = null, $activated = null, $groups = [], $permissions = [])
	{
		$user->named($firstName, $lastName);

		$user->changeEmail($email);

		if ($password !== null)
		{
			$user->changePassword($this->userRepository->hash($password));
		}

		$user->setAllGroups($this->groupService->findAll($groups));
		$user->setAllPermissions($permissions);

		if ($activated && ! $user->isActivated())
		{
			$user->forceActivation();
		}
		elseif ($activated === false && $user->isActivated())
		{
			$user->deactivate();
		}

		$user->save();

		return $user;
	}

	public function delete($id)
	{
		$user = $this->userRepository->findById($id);

		$user->delete();
	}

	public function search($email = null, $firstName = null, $lastName = null, $activated = null, $orderBy = null, $orderSense = 'asc', $limit = 10, $offset = 0)
	{
		$filters = [];

		$expressionBuilder = Criteria::expr();

		if ($email !== null)
		{
			$filters[] = $expressionBuilder->contains('email', $email);
		}

		if ($firstName !== null)
		{
			$filters[] = $expressionBuilder->contains('firstName', $firstName);
		}

		if ($lastName !== null)
		{
			$filters[] = $expressionBuilder->contains('lastName', $lastName);
		}

		if ($activated !== null)
		{
			$filters[] = $expressionBuilder->eq('activated', (boolean) $activated);
		}

		$criteria = Criteria::create();

		if (!empty($filters))
		{
			$criteria->where($expressionBuilder->andX(...$filters));
		}

		if ($orderBy && $orderSense)
		{
			$criteria->orderBy([
				$orderBy => $orderSense
			]);
		}

		$criteria->setMaxResults($limit);
		$criteria->setFirstResult($offset);

		return $this->userRepository->matching($criteria);
	}

	public function findByLogin($login)
	{
		return $this->userRepository->findByLogin($login);
	}
}

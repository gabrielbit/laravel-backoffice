<?php namespace Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Hashing\HasherInterface;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Digbang\L4Backoffice\Auth\Contracts\User as UserInterface;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Digbang\L4Backoffice\Auth\Entities\User;
use Digbang\L4Backoffice\Support\Collection;
use Doctrine\Common\Collections\Criteria;
use InvalidArgumentException;
use RuntimeException;
use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Users\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Config\Repository;

class DoctrineUserRepository extends EntityRepository implements ProviderInterface
{
    /**
     * @type HasherInterface
     */
    private $hasher;

	/**
	 * @type string
	 */
	private $entityName;

    public function __construct(EntityManagerInterface $em, Repository $config, HasherInterface $hasher)
    {
        parent::__construct($em, $em->getClassMetadata(
            $this->entityName = $config->get('security::auth.users.model', User::class)
        ));

        $this->hasher = $hasher;
    }

    /**
     * Finds a user by the given user ID.
     *
     * @param  mixed $id
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function findById($id)
    {
        $user = $this->find($id);

        if (! $user)
        {
            throw new UserNotFoundException("User $id not found.");
        }

        return $this->user($user);
    }

    /**
     * Finds a user by the login value.
     *
     * @param  string $login
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function findByLogin($login)
    {
        $user = $this->findOneBy(['email' => $login]);

        if (! $user)
        {
            throw new UserNotFoundException("User $login not found.");
        }

        return $this->user($user);
    }

    /**
     * Finds a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function findByCredentials(array $credentials)
    {
        $user = $this->findByLogin($credentials['email']);

        if (! $user)
        {
            throw new UserNotFoundException("User " . $credentials['email'] . " not found.");
        }

	    if (! $this->checkHash($credentials['password'], $user->getPassword()))
	    {
		    throw new WrongPasswordException(
			    "A user was found, but passwords did not match."
		    );
	    }

	    if (
		    method_exists($this->hasher, 'needsRehashed') &&
		    $this->hasher->needsRehashed($user->getPassword())
	    )
	    {
		    // The algorithm used to create the hash is outdated and insecure.
		    // Rehash the password and save.
		    $user->changePassword($this->hasher->hash($credentials['password']));
		    $user->save();
	    }

        return $this->user($user);
    }

    /**
     * Finds a user by the given activation code.
     *
     * @param  string $code
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findByActivationCode($code)
    {
        $user = $this->findOneBy(['activationCode' => $code]);

        if (! $user)
        {
            throw new UserNotFoundException("User with code $code not found.");
        }

        return $this->user($user);
    }

    /**
     * Finds a user by the given reset password code.
     *
     * @param  string $code
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     * @throws RuntimeException
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function findByResetPasswordCode($code)
    {
        $user = $this->findOneBy(['resetPasswordCode' => $code]);

        if (! $user)
        {
            throw new UserNotFoundException("User with code $code not found.");
        }

        return $this->user($user);
    }

    /**
     * Returns all users who belong to
     * a group.
     *
     * @param  \Cartalyst\Sentry\Groups\GroupInterface $group
     *
     * @return Collection
     */
    public function findAllInGroup(GroupInterface $group)
    {
        return $this->userCollection(
            $this->findBy([
                'Group' => $group
            ])
        );
    }

    /**
     * Returns all users with access to
     * a permission(s).
     *
     * @param  string|array $permissions
     *
     * @return Collection
     */
    public function findAllWithAccess($permissions)
    {
        $criterias = [];

        foreach ((array) $permissions as $permission)
        {
            $criterias[] = Criteria::expr()->contains('Permissions', $permission);
        }

        return $this->userCollection(
            $this->findBy($criterias)
        );
    }

    /**
     * Returns all users with access to
     * any given permission(s).
     *
     * @param  array $permissions
     *
     * @return Collection
     */
    public function findAllWithAnyAccess(array $permissions)
    {
        $expressionBuilder = Criteria::expr();

        $permissions = (array) $permissions;

        $criterias = [];

        if (!empty($permissions))
        {
            $criterias[] = call_user_func_array([$expressionBuilder, 'orX'], array_map(function($permission){
                return Criteria::expr()->contains('Permissions', $permission);
            }, $permissions));
        }

        return $this->userCollection(
            $this->findBy($criterias)
        );
    }

    /**
     * Creates a user.
     *
     * @param  array $credentials
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     */
    public function create(array $credentials)
    {
	    $entityName = $this->entityName;

	    $user = $entityName::createFromCredentials(
		    $credentials['email'],
		    $this->hasher->hash($credentials['password'])
	    );

        $this->save($user);

        return $this->user($user);
    }

    /**
     * Returns an empty user object.
     *
     * @return \Digbang\L4Backoffice\Auth\Contracts\User
     */
    public function getEmptyUser()
    {
        return $this->user(
            (new \ReflectionClass($this->entityName))->newInstanceWithoutConstructor()
        );
    }

    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();
    }

    /**
     * @param UserInterface $user
     */
    public function delete(UserInterface $user)
    {
        $em = $this->getEntityManager();

        $em->remove($user);
        $em->flush();
    }

    /**
     * @param string $string
     * @param string $hashedString
     *
     * @return bool
     */
    public function checkHash($string, $hashedString)
    {
        return $this->hasher->checkhash($string, $hashedString);
    }

    private function user(UserInterface $user)
    {
	    if ($user instanceof RepositoryAware)
	    {
		    $user->setRepository($this);
	    }

        return $user;
    }

    private function userCollection(array $users)
    {
        return (new Collection($users))->map(function(UserInterface $user){
            return $this->user($user);
        });
    }

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function hash($string)
	{
		return $this->hasher->hash($string);
	}
}

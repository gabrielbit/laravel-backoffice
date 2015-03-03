<?php namespace Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Hashing\HasherInterface;
use Cartalyst\Sentry\Users\UserInterface;
use Cartalyst\Sentry\Users\UserNotFoundException;
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

    public function __construct(EntityManagerInterface $em, Repository $config, HasherInterface $hasher)
    {
        parent::__construct($em, $em->getClassMetadata(
            $config->get('security::auth.users.model')
        ));

        $this->hasher = $hasher;
    }

    /**
     * Finds a user by the given user ID.
     *
     * @param  mixed $id
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
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
     * @return \Cartalyst\Sentry\Users\UserInterface
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
     * @return \Cartalyst\Sentry\Users\UserInterface
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function findByCredentials(array $credentials)
    {
        $user = $this->findOneBy($credentials);

        if (! $user)
        {
            throw new UserNotFoundException("User " . $credentials['email'] . " not found.");
        }

        return $this->user($user);
    }

    /**
     * Finds a user by the given activation code.
     *
     * @param  string $code
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
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
     * @return \Cartalyst\Sentry\Users\UserInterface
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
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function create(array $credentials)
    {
        $user = new User($credentials['email'], $credentials['password']);

        $this->save($user);

        return $this->user($user);
    }

    /**
     * Returns an empty user object.
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     */
    public function getEmptyUser()
    {
        return $this->user(
            (new \ReflectionClass(User::class))->newInstanceWithoutConstructor()
        );
    }

    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush($user);
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

    private function user(User $user)
    {
        $user->setUserRepository($this);

        return $user;
    }

    private function userCollection(array $users)
    {
        return (new Collection($users))->map(function(User $user){
            return $this->user($user);
        });
    }
}

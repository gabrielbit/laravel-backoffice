<?php namespace Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Throttling\ProviderInterface as ThrottleProvider;
use Digbang\L4Backoffice\Auth\Contracts\RepositoryAware;
use Digbang\L4Backoffice\Auth\Contracts\Throttle as ThrottleInterface;
use Cartalyst\Sentry\Users\UserInterface;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Digbang\L4Backoffice\Auth\Entities\Throttle;
use Digbang\L4Backoffice\Auth\Entities\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Illuminate\Config\Repository;

class DoctrineThrottleRepository extends EntityRepository implements ThrottleProvider
{
    /**
     * @type ExpressionBuilder
     */
    private $expr;

    /**
     * Throttling status.
     *
     * @var bool
     */
    private $enabled = true;

	/**
	 * @type string
	 */
	private $entityName;

    public function __construct(EntityManagerInterface $em, Repository $config)
    {
        parent::__construct($em, $em->getClassMetadata(
	        $this->entityName = $config->get('security::auth.throttling.model', Throttle::class)
        ));

        $this->expr = Criteria::expr();
    }

    /**
     * Finds a throttler by the given user ID.
     *
     * @param  UserInterface $user
     * @param  string        $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUser(UserInterface $user, $ipAddress = null)
    {
        $criteria = [
            $this->expr->eq('user', $user)
        ];

        if ($ipAddress)
        {
            $criteria[] = $this->orIpAddressCriteria($ipAddress);
        }

        $throttle = $this->findOneBy($criteria);

        if (! $throttle)
        {
	        $entityName = $this->entityName;
            $throttle = $entityName::create($user, $ipAddress);

            $this->save($throttle);
        }

        return $this->throttle($throttle);
    }

    /**
     * Finds a throttler by the given user ID.
     *
     * @param integer $id
     * @param string  $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUserId($id, $ipAddress = null)
    {
        $user = $this->_em->getPartialReference(User::class, $id);

        if ($user === null)
        {
            throw new UserNotFoundException("A user could not be found with ID [$id].");
        }

        return $this->findByUser($user, $ipAddress);
    }

    /**
     * Finds a throttling interface by the given user login.
     *
     * @param string $login
     * @param string $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUserLogin($login, $ipAddress = null)
    {
        $user = $this->_em->getPartialReference(User::class, ['email' => $login]);

        if ($user === null)
        {
            throw new UserNotFoundException("A user could not be found with a login value of [$login].");
        }

        return $this->findByUser($user, $ipAddress);
    }

    /**
     * Enable throttling.
     *
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable throttling.
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Check if throttling is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    public function save(ThrottleInterface $throttle)
    {
        $em = $this->getEntityManager();

        $em->persist($throttle);
        $em->flush($throttle);
    }

    /**
     * @param $ipAddress
     *
     * @return \Doctrine\Common\Collections\Expr\CompositeExpression
     */
    private function orIpAddressCriteria($ipAddress)
    {
        return $this->expr->orX(
            $this->expr->eq('ipAddress', $ipAddress),
            $this->expr->isNull('ipAddress')
        );
    }

    private function throttle(ThrottleInterface $throttle)
    {
	    if ($throttle instanceof RepositoryAware)
	    {
		    $throttle->setRepository($this);
	    }

        return $throttle;
    }
}

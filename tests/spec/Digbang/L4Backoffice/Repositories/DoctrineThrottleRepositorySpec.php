<?php namespace spec\Digbang\L4Backoffice\Repositories;

use Cartalyst\Sentry\Throttling\ProviderInterface;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Digbang\L4Backoffice\Auth\Entities\Throttle;
use Digbang\L4Backoffice\Auth\Entities\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Illuminate\Config\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class DoctrineThrottleRepositorySpec
 *
 * @package spec\Digbang\L4Backoffice\Repositories
 * @mixin \Digbang\L4Backoffice\Repositories\DoctrineThrottleRepository
 */
class DoctrineThrottleRepositorySpec extends ObjectBehavior
{
    private $user;

    function let(
        EntityManagerInterface $em,
        ClassMetadata $cm,
        UnitOfWork $uow,
        EntityPersister $ep,
		Repository $config
    )
    {
	    $config->get('security::auth.throttling.model', Throttle::class)->willReturn(Throttle::class);
	    $this->user = new User('testing', 'asd');
        $throttle = new Throttle($this->user, '127.0.0.1');

        $cm->name = Throttle::class;
        $em->getClassMetadata(Throttle::class)->willReturn($cm);
        $em->getUnitOfWork()->willReturn($uow);
        $uow->getEntityPersister(Throttle::class)->willReturn($ep);

        // Successful find by ID
        $em->find(Throttle::class, 1, Argument::cetera())->willReturn($throttle);
        // Successful find by user
        $ep->load([Criteria::expr()->eq('user', $this->user)], Argument::cetera())->willReturn($throttle);
        // Successful find by user and ip address
        $ep->load(Argument::allOf(
            Argument::containing(Criteria::expr()->eq('user', $this->user)),
            Argument::containing(Argument::type(CompositeExpression::class))
        ), Argument::cetera())->willReturn($throttle);
        // Successful find collection by group / permissions
        $ep->loadAll(Argument::cetera())->willReturn([$throttle]);

        // Failed to find by id
        $em->find(Throttle::class, Argument::not(1), Argument::cetera())->willReturn(null);
        // Failed to find by everything else
        $ep->load(Argument::cetera())->willReturn(null);

        $this->beConstructedWith($em, $config);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Repositories\DoctrineThrottleRepository');
    }

    function it_should_implement_sentrys_provider_interface()
    {
        $this->shouldHaveType(ProviderInterface::class);
    }

    function it_should_find_throttles_by_user()
    {
        $this->findByUser($this->user)->shouldBeAnInstanceOf(Throttle::class);
    }

    function it_should_find_throttles_by_user_and_ip_address()
    {
        $this->findByUser($this->user, '127.0.0.1')->shouldBeAnInstanceOf(Throttle::class);
    }

    function it_should_create_and_save_a_throttle_if_it_doesnt_exist(EntityManagerInterface $em)
    {
        /** @type Double $em */
        $em->persist(Argument::type(Throttle::class))->shouldBeCalled();
        $em->flush(Argument::type(Throttle::class))->shouldBeCalled();

        $this->findByUser(new User('guiwoda@gmail.com', 'my_real_password'))
            ->shouldBeAnInstanceOf(Throttle::class);
    }

    function it_should_find_throttles_by_user_id(EntityManagerInterface $em)
    {
        $em->getPartialReference(User::class, 1)->willReturn($this->user);

        $this->findByUserId(1)->shouldBeAnInstanceOf(Throttle::class);
    }

    function it_should_throw_an_exception_when_user_id_doesnt_exist(EntityManagerInterface $em)
    {
        $em->getPartialReference(User::class, 2)->willReturn(null);

        $this->shouldThrow(UserNotFoundException::class)->duringFindByUserId(2);
    }

    function it_should_find_throttles_by_user_login(EntityManagerInterface $em)
    {
        $em->getPartialReference(User::class, ['email' => 'testing'])->willReturn($this->user);

        $this->findByUserLogin('testing')->shouldBeAnInstanceOf(Throttle::class);
    }

    function it_should_throw_an_exception_when_user_login_doesnt_exist(EntityManagerInterface $em)
    {
        $em->getPartialReference(User::class, ['email' => 'guiwoda@gmail.com'])->willReturn(null);

        $this->shouldThrow(UserNotFoundException::class)->duringFindByUserLogin('guiwoda@gmail.com');
    }

    function it_should_be_enabled_by_default()
    {
        $this->isEnabled()->shouldReturn(true);
    }

    function it_should_be_able_to_be_disabled_and_reenabled()
    {
        $this->disable();

        $this->isEnabled()->shouldReturn(false);

        $this->enable();

        $this->isEnabled()->shouldReturn(true);
    }


}
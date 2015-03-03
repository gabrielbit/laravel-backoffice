<?php namespace spec\Digbang\L4Backoffice;

use Cartalyst\Sentry\Users\ProviderInterface      as UsersProvider;
use Cartalyst\Sentry\Groups\ProviderInterface     as GroupsProvider;
use Cartalyst\Sentry\Throttling\ProviderInterface as ThrottlingProvider;
use Digbang\L4Backoffice\Repositories\DoctrineGroupRepository;
use Digbang\L4Backoffice\Repositories\DoctrineThrottleRepository;
use Digbang\L4Backoffice\Repositories\DoctrineUserRepository;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

/**
 * Class BackofficeServiceProviderSpec
 *
 * @package spec\Digbang\L4Backoffice
 * @mixin \Digbang\L4Backoffice\BackofficeServiceProvider
 */
class BackofficeServiceProviderSpec extends ObjectBehavior
{
    function let(Application $app)
    {
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\BackofficeServiceProvider');
        $this->shouldHaveType('Illuminate\Support\ServiceProvider');
    }

    function it_should_bind_providers(Repository $config, Application $app)
    {
        /**
         * @type Collaborator $app
         */
        $app->offsetGet('config')->willReturn($config);

        $app->offsetExists(UsersProvider::class)->willReturn(false);
        $app->offsetExists(GroupsProvider::class)->willReturn(false);
        $app->offsetExists(ThrottlingProvider::class)->willReturn(false);

        $app->bind(UsersProvider::class, DoctrineUserRepository::class, true)->shouldBeCalled();
        $app->bind(GroupsProvider::class, DoctrineGroupRepository::class, true)->shouldBeCalled();
        $app->bind(ThrottlingProvider::class, DoctrineThrottleRepository::class, true)->shouldBeCalled();

        $app->offsetGet(Argument::any())->willReturn(null);
        $app->singleton(Argument::cetera())->willReturn(null);
        $app->bind(Argument::cetera())->willReturn(null);
        $app->register(Argument::cetera())->willReturn(null);

        $config->get('security::auth.driver')->willReturn('custom');

        $this->register();
    }
}

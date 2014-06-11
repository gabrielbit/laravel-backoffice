<?php namespace spec\Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory as FilterFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListingFactorySpec extends ObjectBehavior
{
	function let()
	{
		$controlFactory = new ControlFactory();
		$filterFactory = new FilterFactory($controlFactory);
		$actionFactory = new ActionFactory($controlFactory);

		$this->beConstructedWith($filterFactory, $actionFactory);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listings\ListingFactory');
    }

	function it_should_make_listings()
	{
		$this->make()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\Listing');
	}
}

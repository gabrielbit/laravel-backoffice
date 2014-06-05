<?php namespace spec\Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Filters\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListingFactorySpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new Factory(new ControlFactory()));
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

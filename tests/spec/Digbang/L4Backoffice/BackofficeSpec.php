<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Filters\Factory as FilterFactory;
use Digbang\L4Backoffice\ListingFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackofficeSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new ListingFactory(new FilterFactory()));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Backoffice');
    }

	function it_is_a_facade_for_the_listing_factory()
	{
		$this->listing()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listing');
	}

	function it_is_a_facade_for_the_breadcrumb_factory()
	{
		$this->breadcrumb([
			'Home' => 'http://the.url/',
			'Category' => 'http://the.url/category',
			'Category Item'
		])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Breadcrumb');
	}
}

<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Digbang\L4Backoffice\Filters\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Digbang\L4Backoffice\Listing
 * @package spec\Digbang\L4Backoffice
 */
class ListingSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new FilterCollection(new Factory()));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listing');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Illuminate\\Support\\Collection');
	}

	function it_should_have_a_filters_collection()
	{
		$this->filters()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Collection');
	}

	function it_should_give_me_a_specific_filter_by_name()
	{
		$this->filters()->text('some_filter');

		$this->filters('some_filter')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Text');
	}
}

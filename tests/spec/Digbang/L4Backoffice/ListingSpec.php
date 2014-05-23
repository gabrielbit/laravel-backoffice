<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Digbang\L4Backoffice\Filters\Factory;
use Illuminate\View\Environment;
use Illuminate\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Digbang\L4Backoffice\Listing
 * @package spec\Digbang\L4Backoffice
 */
class ListingSpec extends ObjectBehavior
{
	private $viewFactory;

	function let(Environment $viewFactory)
	{
		$this->viewFactory = $viewFactory;

		$this->beConstructedWith($this->viewFactory, new FilterCollection(new Factory()));
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

	function it_should_be_renderable(View $view)
	{
		// Mock the view factory's make method
		$this->viewFactory->make($this->getView(), $this->toArray())->willReturn($view);

		// And mock the view render method
		$view->render()->willReturn('I render thee');

		$this->render()->shouldReturn('I render thee');
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

<?php

namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
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
	private $message = 'I render thee';
	private $viewFactory;

	function let(Environment $viewFactory, FilterCollection $filterCollection)
	{
		$this->viewFactory = $viewFactory;

		$this->beConstructedWith($this->viewFactory, $filterCollection);
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
		$view->render()->willReturn($this->message);

		$this->render()->shouldReturn($this->message);
	}
}

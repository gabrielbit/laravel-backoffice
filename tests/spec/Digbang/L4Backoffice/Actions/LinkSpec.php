<?php namespace spec\Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LinkSpec extends ObjectBehavior
{
	function let(Environment $viewFactory)
	{
		$this->beConstructedWith($viewFactory);
	}

    function it_is_initializable()
    {
	    $this->shouldHaveType('Digbang\L4Backoffice\Actions\ActionInterface');
    }

}

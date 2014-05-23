<?php namespace spec\Digbang\L4Backoffice\Filters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('aName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\Text');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }
}

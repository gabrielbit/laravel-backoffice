<?php namespace spec\Digbang\L4Backoffice\Filters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropDownSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('aName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\DropDown');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }
}

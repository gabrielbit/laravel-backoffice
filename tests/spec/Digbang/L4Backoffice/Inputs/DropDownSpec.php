<?php namespace spec\Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropDownSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
		$this->beConstructedWith($controlFactory->make('someView', 'someLabel', ['some' => 'opts']), 'someName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Inputs\DropDown');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }
}

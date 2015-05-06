<?php namespace spec\Digbang\L4Backoffice\Controls;

use Illuminate\View\Factory;
use Illuminate\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControlSpec extends ObjectBehavior
{
	function let(Factory $factory, View $view)
	{
		$factory->make(Argument::cetera())->willReturn($view);

		$this->beConstructedWith($factory, 'a-view', 'A Label', ['some' => 'options']);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Controls\Control');
    }
}

<?php namespace spec\Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class InputSpec
 * @mixin \Digbang\L4Backoffice\Inputs\Input
 * @package spec\Digbang\L4Backoffice\Inputs
 */
class InputSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
		$this->beConstructedWith($controlFactory->make('someView', 'someLabel', ['some' => 'opts']), 'someName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Inputs\Input');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_let_me_get_a_specific_option()
	{
		$this->options('some')->shouldNotBe(null);
	}
}

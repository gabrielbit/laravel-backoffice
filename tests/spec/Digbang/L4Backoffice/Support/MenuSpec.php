<?php namespace spec\Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\Composite;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class MenuSpec
 * @mixin \Digbang\L4Backoffice\Support\Menu
 * @package spec\Digbang\L4Backoffice\Support
 */
class MenuSpec extends ObjectBehavior
{
	function let(ControlInterface $control, Composite $actionTree,View $view)
	{
		$control->render(Argument::cetera())->willReturn($view);

		$view->with(Argument::cetera())->willReturn($view);

		$this->beConstructedWith($control, $actionTree);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Support\Menu');
    }

	function it_should_be_renderable()
	{
		$this->render()->shouldBeAnInstanceOf('Illuminate\View\View');
	}
}

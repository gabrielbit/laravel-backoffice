<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Forms\Form;
use Illuminate\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ModalSpec extends ObjectBehavior
{
	function let(Form $form, ControlInterface $control, View $view)
	{
		$control->render()->willReturn($view);
		$view->with(Argument::any())->willReturn($view);

		$this->beConstructedWith($form, 'aUniqueId', $control, 'anIcon');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Modal');
    }

	function it_should_be_renderable()
	{
		$this->render()->shouldBeAnInstanceOf('Illuminate\View\View');
	}
}

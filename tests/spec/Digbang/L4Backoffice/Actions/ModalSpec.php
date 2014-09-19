<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Forms\Form;
use Illuminate\Support\Collection;
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

	function it_should_be_renderable_with_a_row()
	{
		$this->renderWith(new Collection(['some' => 'items']))->shouldBeAnInstanceOf('Illuminate\View\View');
	}
}

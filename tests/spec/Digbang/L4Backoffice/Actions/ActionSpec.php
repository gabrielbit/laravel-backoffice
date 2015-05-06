<?php

namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ActionSpec
 *
 * @package spec\Digbang\L4Backoffice\Actions
 * @mixin \Digbang\L4Backoffice\Actions\Action
 */
class ActionSpec extends ObjectBehavior
{
	function let(ControlInterface $control, Collection $options, View $view)
	{
		$control->options()->willReturn($options);
		$control->label()->willReturn('A Label');
		$control->view()->willReturn('a-view');
		$control->render()->willReturn($view);

		$this->beConstructedWith($control, 'foo', null);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Action');
    }

	function it_should_render_with_callable_options(ControlInterface $control, Collection $options)
	{
		$control->render()->shouldBeCalled();

		$options->getIterator()->willReturn(new Collection([
			'onclick' => function(Collection $row) {
				return $row['id'];
			}
		]));

		$control->changeOption('onclick', 2)->shouldBeCalled()->willReturn($control);

		$this->renderWith([
			'id' => 2
		]);
	}

	function it_should_render_properly_multiple_callable_options(ControlInterface $control, Collection $options)
	{
		$control->render()->shouldBeCalled();

		$options->getIterator()->willReturn(new Collection([
			'onclick' => function(Collection $row) {
				return $row['id'];
			},
			'onfoo' => function(Collection $row) {
				return $row['name'];
			}
		]));

		$control->changeOption('onclick', 2)->shouldBeCalled()->willReturn($control);
		$control->changeOption('onfoo', 'John')->shouldBeCalled()->willReturn($control);

		$this->renderWith([
			'id' => 2,
			'name' => 'John'
		]);

		$control->changeOption('onclick', 3)->shouldBeCalled()->willReturn($control);
		$control->changeOption('onfoo', 'Mary')->shouldBeCalled()->willReturn($control);

		$this->renderWith([
			'id' => 3,
			'name' => 'Mary'
		]);
	}

	function it_should_render_with_no_callable_options(ControlInterface $control, Collection $options)
	{
		$options->getIterator()->willReturn(
			new Collection(['onclick' => 'return false;'])
		);

		$control->render()->shouldBeCalled();

		$this->renderWith([
			'id' => 2
		]);

		$control->changeOption('onclick', Argument::any())->shouldNotHaveBeenCalled();
	}
}

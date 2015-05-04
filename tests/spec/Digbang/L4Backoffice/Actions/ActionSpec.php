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
	function let(ControlInterface $control)
	{
		$this->beConstructedWith($control, 'foo', null);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Action');
    }

	function it_should_render_with_callable_options(ControlInterface $control, Collection $options, View $view)
	{
		$control->render()->shouldBeCalled()->willReturn($view);
		$control->options(null)->willReturn($options);

		$options->put('onclick', Argument::any())->shouldBeCalled();
		$options->getIterator()->willReturn(
			new Collection(['onclick' => function(Collection $row){
				return $row['id'];
			}])
		);

		$this->renderWith([
			'id' => 2
		]);

		$options->put('onclick', 2)->shouldHaveBeenCalled();
	}

	function it_should_render_with_no_callable_options(ControlInterface $control, Collection $options, View $view)
	{
		$control->render()->shouldBeCalled()->willReturn($view);
		$control->options(null)->willReturn($options);
		$options->getIterator()->willReturn(
			new Collection(['onclick' => 'return false;'])
		);

		$this->renderWith([
			'id' => 2
		]);

		$options->put('onclick', Argument::any())->shouldNotHaveBeenCalled();
	}
}

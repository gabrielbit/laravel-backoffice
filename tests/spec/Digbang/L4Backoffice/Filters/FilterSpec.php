<?php namespace spec\Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class FilterSpec
 * @mixin \Digbang\L4Backoffice\Filters\Filter
 * @package spec\Digbang\L4Backoffice\Filters
 */
class FilterSpec extends ObjectBehavior
{
	function let()
	{
		$controlFactory = new ControlFactory();
		$this->beConstructedWith($controlFactory->make('someView', 'someLabel', ['some' => 'opts']), 'someName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\Filter');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_let_me_get_a_specific_option()
	{
		$this->options('some')->shouldNotBe(null);
	}
}

<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormSpec extends ObjectBehavior
{
	function let()
	{
		$factory = new Factory(new ControlFactory());

		$this->beConstructedWith($factory->collection());
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Forms\Form');
    }

	function it_should_hold_a_collection_of_inputs()
	{
		$this->inputs()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Collection');
	}
}
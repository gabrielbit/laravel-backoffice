<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormSpec extends ObjectBehavior
{
	function let()
	{
		$controlFactory = new ControlFactory();
		$factory = new Factory($controlFactory);

		$this->beConstructedWith(
			$controlFactory->make('some-view', 'Some Label', ['some' => 'options']),
			$factory->collection()
		);
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

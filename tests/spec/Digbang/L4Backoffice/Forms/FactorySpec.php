<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory as InputFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
	function let()
	{
		$inputFactory = new InputFactory(new ControlFactory());

		$this->beConstructedWith($inputFactory);
	}
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Forms\Factory');
    }

	function it_should_create_forms()
	{
		$this->make()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Forms\Form');
	}
}

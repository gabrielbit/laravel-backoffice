<?php

namespace spec\Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new ControlFactory());
	}

	function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\Factory');
    }

	function it_should_create_text_filters()
	{
		$this->text('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Filter');
		$this->text('some_name', 'Some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Filter');
		$this->text('some_name', 'Another label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Filter');
	}

	function it_should_create_dropdown_filters()
	{
		$this->dropdown('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\DropDown');
		$this->dropdown('some_name', 'some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\DropDown');
		$this->dropdown('some_name', 'Some Label', ['some' => 'data'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\DropDown');
		$this->dropdown('some_name', 'Some Label', ['some' => 'data'], ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\DropDown');
	}
}

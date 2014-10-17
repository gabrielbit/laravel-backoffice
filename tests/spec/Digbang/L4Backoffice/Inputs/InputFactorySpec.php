<?php

namespace spec\Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class InputFactorySpec
 *
 * @package spec\Digbang\L4Backoffice\Inputs
 * @mixin \Digbang\L4Backoffice\Inputs\InputFactory
 */
class InputFactorySpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$this->beConstructedWith(new ControlFactory($viewFactory->getWrappedObject()));
	}

	function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Inputs\InputFactory');
    }

	function it_should_create_text_filters()
	{
		$this->text('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
		$this->text('some_name', 'Some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
		$this->text('some_name', 'Another label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
	}

	function it_should_create_dropdown_filters()
	{
		$this->dropdown('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\DropDown');
		$this->dropdown('some_name', 'some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\DropDown');
		$this->dropdown('some_name', 'Some Label', ['some' => 'data'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\DropDown');
		$this->dropdown('some_name', 'Some Label', ['some' => 'data'], ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\DropDown');
	}

	function it_should_create_password_inputs()
	{
		$this->password('some_name', 'Some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
		$this->password('some_name', 'Another label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
	}

	function it_should_create_textarea_filters()
	{
		$this->textarea('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
		$this->textarea('some_name', 'Some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
		$this->textarea('some_name', 'Another label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
	}
}

<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ControllerInput;
use Digbang\L4Backoffice\Generator\Services\Generator;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

/**
 * Class ControllerGeneratorSpec
 *
 * @package spec\Digbang\L4Backoffice\Generator\Services
 * @mixin \Digbang\L4Backoffice\Generator\Services\ControllerGenerator
 */
class ControllerGeneratorSpec extends ObjectBehavior
{
	function let(Generator $generator, ControllerInput $controllerInput)
	{
		$this->beConstructedWith($generator, $controllerInput);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Services\ControllerGenerator');
    }

	function it_should_be_fluent_with_setting_the_template()
	{
		$this->fromTemplate('.')->shouldReturn($this);
	}

	function it_should_validate_the_template_path()
	{
		$this->shouldThrow(\UnexpectedValueException::class)->duringFromTemplate('a_non_existing_file');
	}

	function it_should_be_fluent_with_setting_the_controllers_directory()
	{
		$this->toDir(__DIR__)->shouldReturn($this);
	}

	function it_should_validate_the_controllers_directory()
	{
		$this->shouldThrow(\UnexpectedValueException::class)->duringToDir(__FILE__);
		$this->shouldThrow(\UnexpectedValueException::class)->duringToDir('a_non_existing_directory');
	}

	function it_should_be_fluent_with_setting_the_controllers_namespace()
	{
		$this->inNamespace('Whatever\\Namespace')->shouldReturn($this);
	}

	function it_should_be_fluent_with_setting_the_api()
	{
		$this->withApi('Whatever\\Api\\Class')->shouldReturn($this);
	}

	function it_should_be_fluent_with_adding_methods()
	{
		$this->addMethod('index', 'search', ['a', 'b', 'c'])->shouldReturn($this);
	}

	/**
	 * CRUDIE: Create, Read, Update, Delete, Index and Export
	 */
	function it_should_validate_crudie_methods()
	{
		$this->addMethod('create', 'build',   ['a', 'b', 'c'])->shouldReturn($this);
		$this->addMethod('read',   'find',    ['a', 'b', 'c'])->shouldReturn($this);
		$this->addMethod('UPDATE', 'save',    ['a', 'b', 'c'])->shouldReturn($this);
		$this->addMethod('DelEtE', 'destroy', ['a', 'b', 'c'])->shouldReturn($this);
		$this->addMethod('index',   'search',  ['a', 'b', 'c'])->shouldReturn($this);
		$this->addMethod('eXpOrT', 'search',  ['a', 'b', 'c'])->shouldReturn($this);

		$this->shouldThrow(\UnexpectedValueException::class)
			->duringAddMethod('fascinate', 'doFascination', ['a', 'b', 'c']);
	}

	function it_should_delegate_making_the_controller_to_the_generator(Generator $generator)
	{
		/** @type Collaborator $generator */
		$generator->make(Argument::any(), Argument::any(), Argument::type(ControllerInput::class))
			->shouldBeCalled();

		$this->generate();
	}
}

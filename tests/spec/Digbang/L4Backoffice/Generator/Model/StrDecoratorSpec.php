<?php namespace spec\Digbang\L4Backoffice\Generator\Model;

use Digbang\L4Backoffice\Generator\Model\StrDecorator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class StrDecoratorSpec
 *
 * @package spec\Digbang\L4Backoffice\Generator\Model
 * @mixin \Digbang\L4Backoffice\Generator\Model\StrDecorator
 */
class StrDecoratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Model\StrDecorator');
    }

	function it_should_convert_a_given_string_to_studly_case()
	{
		$this->setString('testingWithCamelCase');
		$this->studly()->__toString()->shouldReturn('TestingWithCamelCase');

		$this->setString('testing_with_snake_case');
		$this->studly()->__toString()->shouldReturn('TestingWithSnakeCase');

		$this->setString('Testing with spaced text');
		$this->studly()->__toString()->shouldReturn('TestingWithSpacedText');
	}

	function it_should_convert_a_given_string_to_title_case()
	{
		$this->setString('testingWithCamelCase');
		$this->title()->__toString()->shouldReturn('Testing With Camel Case');

		$this->setString('testing_with_snake_case');
		$this->title()->__toString()->shouldReturn('Testing With Snake Case');

		$this->setString('Testing with spaced text');
		$this->title()->__toString()->shouldReturn('Testing With Spaced Text');
	}

	function it_should_convert_a_given_string_to_plural()
	{
		$this->setString('testingWithCamelCase');
		$this->plural()->__toString()->shouldReturn('testingWithCamelCases');

		$this->setString('testing_with_snake_case');
		$this->plural()->__toString()->shouldReturn('testing_with_snake_cases');

		$this->setString('Testing with spaced text');
		$this->plural()->__toString()->shouldReturn('Testing with spaced texts');
	}

	function it_should_convert_a_given_string_to_snake_case()
	{
		$this->setString('testingWithCamelCase');
		$this->snake()->__toString()->shouldReturn('testing_with_camel_case');

		$this->setString('testing_with_snake_case');
		$this->snake()->__toString()->shouldReturn('testing_with_snake_case');

		$this->setString('Testing with spaced text');
		$this->snake()->__toString()->shouldReturn('testing_with_spaced_text');
	}

	function it_should_always_return_an_instance_of_itself()
	{
		$this->setString('i_dont_care');

		$this->studly()->shouldBeAnInstanceOf(StrDecorator::class);
		$this->title()->shouldBeAnInstanceOf(StrDecorator::class);
		$this->plural()->shouldBeAnInstanceOf(StrDecorator::class);
		$this->snake()->shouldBeAnInstanceOf(StrDecorator::class);
	}
}

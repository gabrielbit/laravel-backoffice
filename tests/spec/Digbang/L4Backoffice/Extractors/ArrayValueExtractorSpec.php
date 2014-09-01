<?php

namespace spec\Digbang\L4Backoffice\Extractors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Contracts\ArrayableInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayValueExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Extractors\ArrayValueExtractor');
    }

	function it_should_extract_values_from_an_array()
	{
		$key = 'aValidProperty';
		$aValidValue = 'A Valid Value';

		$anArray = [$key => $aValidValue];

		$this->extract($anArray, $key)->shouldReturn($aValidValue);
	}

	function it_should_extract_values_from_an_object_with_array_access(\ArrayAccess $anArrayAccessObject)
	{
		$key = 'aValidProperty';
		$aValidValue = 'A Valid Value';

		$anArrayAccessObject->offsetExists($key)->willReturn(true);
		$anArrayAccessObject->offsetGet($key)->shouldBeCalled()->willReturn($aValidValue);

		$this->extract($anArrayAccessObject, $key)->shouldReturn($aValidValue);
	}

	function it_should_extract_values_from_an_arrayable_object(ArrayableInterface $anArrayableObject)
	{
		$key = 'aValidProperty';
		$aValidValue = 'A Valid Value';

		$anArrayableObject->toArray()->shouldBeCalled()->willReturn([
			$key => $aValidValue
		]);

		$this->extract($anArrayableObject, $key)->shouldReturn($aValidValue);
	}

	function it_should_squeak_when_given_a_non_array_input()
	{
		$stdClass = new \stdClass();
		$stdClass->aProperty = 'A Value';

		$this->shouldThrow('UnexpectedValueException')->duringExtract(
			$stdClass, 'aProperty'
		);
	}
}

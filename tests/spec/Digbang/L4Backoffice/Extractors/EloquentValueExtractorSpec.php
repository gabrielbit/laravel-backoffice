<?php

namespace spec\Digbang\L4Backoffice\Extractors;

use Illuminate\Database\Eloquent\Model;
use \stdClass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class EloquentValueExtractorSpec
 * @package spec\Digbang\L4Backoffice\Extractors
 * @mixin \Digbang\L4Backoffice\Extractors\EloquentValueExtractor
 */
class EloquentValueExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Extractors\EloquentValueExtractor');
    }

	function it_should_extract_values_from_an_eloquent_object(Model $anEloquentObject)
	{
		$key = 'aValidProperty';
		$aValidValue = 'A Valid Value';

		$anEloquentObject->getAttribute($key)->shouldBeCalled()->willReturn($aValidValue);

		$this->extract($anEloquentObject, $key)->shouldReturn($aValidValue);
	}

	function it_should_squeak_when_given_a_non_eloquent_object()
	{
		$this->shouldThrow('UnexpectedValueException')->duringExtract(
			['aProperty' => 'aValue'], 'aProperty'
		);

		$stdClass = new stdClass();
		$stdClass->aProperty = 'A Value';

		$this->shouldThrow('UnexpectedValueException')->duringExtract(
			$stdClass, 'aProperty'
		);
	}
}

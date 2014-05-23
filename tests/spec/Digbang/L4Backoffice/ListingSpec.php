<?php

namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\ColumnCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Digbang\L4Backoffice\Listing
 * @package spec\Digbang\L4Backoffice
 */
class ListingSpec extends ObjectBehavior
{
	function let(ColumnCollection $columnCollection)
	{
		$columnCollection->toArray()->willReturn(array());

		$this->beConstructedWith($columnCollection);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listing');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Illuminate\\Support\\Collection');
	}


}

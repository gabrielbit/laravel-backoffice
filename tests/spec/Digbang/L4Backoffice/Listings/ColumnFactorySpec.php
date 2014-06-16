<?php

namespace spec\Digbang\L4Backoffice\Listings;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listings\ColumnFactory');
    }

	function it_should_make_columns()
	{
		$this->make([
			'name' => 'Name',
			'some' => 'Stuff'
		])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\ColumnCollection');
	}
}

<?php

namespace spec\Digbang\L4Backoffice;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ColumnSpec
 * @mixin \Digbang\L4Backoffice\Column
 * @package spec\Digbang\L4Backoffice
 */
class ColumnSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith($id = uniqid(), "some label for $id", (boolean) rand(0, 1));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Column');
    }
}

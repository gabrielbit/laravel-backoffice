<?php

namespace spec\Digbang\L4Backoffice\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BreadcrumbSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Support\Breadcrumb');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Illuminate\\Support\\Collection');
	}
}
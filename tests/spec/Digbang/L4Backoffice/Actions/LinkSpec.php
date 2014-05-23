<?php namespace spec\Digbang\L4Backoffice\Actions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LinkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
	    $this->shouldHaveType('Digbang\L4Backoffice\Actions\ActionInterface');
    }

}

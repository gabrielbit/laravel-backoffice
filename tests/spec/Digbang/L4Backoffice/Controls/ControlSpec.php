<?php namespace spec\Digbang\L4Backoffice\Controls;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Controls\Control');
    }
}

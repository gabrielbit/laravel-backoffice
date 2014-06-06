<?php namespace spec\Digbang\L4Backoffice\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LinkMakerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Support\LinkMaker');
    }
}

<?php namespace spec\Digbang\L4Backoffice\Support;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LinkMakerSpec extends ObjectBehavior
{
	function let(Request $request)
	{
		$this->beConstructedWith($request);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Support\LinkMaker');
    }
}

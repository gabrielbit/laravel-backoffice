<?php

namespace spec\Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
	function let(Environment $viewFactory)
	{
		$this->beConstructedWith($viewFactory);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Factory');
    }

	function it_should_create_link_actions()
	{
		$this->link('http://google.com')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
		$this->link('http://google.com', 'Google')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
		$this->link('http://google.com', 'Google', ['class' => 'btn btn-primary'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
	}
}

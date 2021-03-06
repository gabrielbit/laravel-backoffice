<?php

namespace spec\Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BreadcrumbSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
		$this->beConstructedWith($controlFactory->make('someView', 'someLabel', ['some' => 'options']));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Support\Breadcrumb');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }
}
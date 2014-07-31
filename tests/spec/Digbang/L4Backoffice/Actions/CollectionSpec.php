<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\Support\Collection;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$this->beConstructedWith(new ActionFactory(new ControlFactory($viewFactory->getWrappedObject())), new Collection());
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Collection');
    }

	function it_should_be_traversable()
	{
		$this->shouldHaveType('Traversable');
	}
}

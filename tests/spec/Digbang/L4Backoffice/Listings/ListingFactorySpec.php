<?php namespace spec\Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\InputFactory as FilterFactory;
use Digbang\L4Backoffice\Actions\ActionFactory as ActionFactory;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Digbang\L4Backoffice\Listings\ColumnFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListingFactorySpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
		$inputFactory = new InputFactory($controlFactory);

		$this->beConstructedWith($inputFactory, $controlFactory);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listings\ListingFactory');
    }

	function it_should_make_listings()
	{
		$columnsFactory = new ColumnFactory();
		$this->make($columnsFactory->make(['some', 'columns']))->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\Listing');
	}
}

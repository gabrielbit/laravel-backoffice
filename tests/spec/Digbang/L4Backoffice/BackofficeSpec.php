<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory as FilterFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Listings\ListingFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackofficeSpec extends ObjectBehavior
{
	function let()
	{
		$controlFactory = new ControlFactory();
		$filterFactory = new FilterFactory($controlFactory);
		$actionFactory = new ActionFactory($controlFactory);

		$this->beConstructedWith(
			new ListingFactory($filterFactory, $actionFactory),
			$actionFactory,
			$controlFactory
		);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Backoffice');
    }

	function it_is_a_facade_for_the_listing_factory()
	{
		$this->listing()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\Listing');
	}

	function it_is_a_facade_for_the_breadcrumb_factory()
	{
		$this->breadcrumb([
			'Home' => 'http://the.url/',
			'Category' => 'http://the.url/category',
			'Category Item'
		])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Support\Breadcrumb');
	}

	function it_is_a_facade_for_the_columns_factory()
	{
		$this->columns([
			'name' => 'Name',
			'some' => 'Stuff'
		])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\ColumnCollection');
	}

	function it_is_a_facade_for_the_actions_factory()
	{
		$this->actions()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Collection');
	}
}

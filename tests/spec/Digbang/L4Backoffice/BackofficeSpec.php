<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Forms\FormFactory;
use Digbang\L4Backoffice\Listings\ColumnFactory;
use Digbang\L4Backoffice\Listings\ListingFactory;
use Illuminate\Session\Store;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackofficeSpec extends ObjectBehavior
{
	function let(Store $session)
	{
		/* @var $session \PhpSpec\Wrapper\Collaborator */
		$controlFactory = new ControlFactory();
		$inputFactory  = new InputFactory($controlFactory);
		$actionFactory  = new ActionFactory($controlFactory);
		$formFactory    = new FormFactory($inputFactory, $actionFactory, $session->getWrappedObject());
		$columnFactory  = new ColumnFactory();

		$this->beConstructedWith(
			new ListingFactory($inputFactory, $actionFactory),
			$actionFactory,
			$controlFactory,
			$formFactory,
			$columnFactory
		);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Backoffice');
    }

	function it_is_a_facade_for_the_listing_factory()
	{
		$this->listing(
			['column' => 'A', 'Some', 'stuff' => 'asd']
		)->shouldBeAnInstanceOf('Digbang\L4Backoffice\Listings\Listing');
	}

	function it_is_a_facade_for_the_breadcrumb_factory()
	{
		$this->breadcrumb([
			'Home' => 'http://the.url/',
			'Category' => 'http://the.url/category',
			'Category Item'
		])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Support\Breadcrumb');
	}

	function it_is_a_facade_for_the_actions_factory()
	{
		$this->actions()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Collection');
	}

	function it_is_a_facade_for_the_form_element()
	{
		$this->form('http://some.url/', 'Some label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Forms\Form');
		$this->form('http://some.url/', 'Some label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Forms\Form');
	}
}

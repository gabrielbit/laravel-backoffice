<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Digbang\L4Backoffice\Actions\ActionFactory;
use Illuminate\Session\Store;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormFactorySpec extends ObjectBehavior
{
	function let(Store $session)
	{
		/* @var $session \PhpSpec\Wrapper\Collaborator */
		$controlFactory = new ControlFactory();
		$inputFactory = new InputFactory($controlFactory);
		$actionFactory = new ActionFactory($controlFactory);

		$this->beConstructedWith($inputFactory, $actionFactory, $session->getWrappedObject());
	}
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Forms\FormFactory');
    }

	function it_should_create_forms()
	{
		$this->make('http://some.url', 'Some Label')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Forms\Form');
		$this->make('http://some.url', 'Some Label', ['some' => 'options'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Forms\Form');
	}
}

<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Inputs\Factory as InputFactory;
use Illuminate\Session\Store;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormSpec extends ObjectBehavior
{
	function let(Store $session)
	{
		/* @var $session \PhpSpec\Wrapper\Collaborator */
		$controlFactory = new ControlFactory();
		$actionFactory = new ActionFactory($controlFactory);
		$inputFactory = new InputFactory($controlFactory);

		$this->beConstructedWith(
			'some-view',
			$actionFactory->form('/target', 'Some Label', 'METHOD_NAME', ['some' => 'options']),
			$inputFactory->collection(),
			$session->getWrappedObject(),
			'/cancel/action',
			['some' => 'options']
		);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Forms\Form');
    }

	function it_should_hold_a_collection_of_inputs()
	{
		$this->inputs()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Collection');
	}

	function it_should_let_me_know_if_it_has_a_file_input()
	{
		$this->hasFile()->shouldReturn(false);
	}
}

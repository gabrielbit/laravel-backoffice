<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Forms\Form;
use Digbang\L4Backoffice\Forms\FormFactory;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ActionFactorySpec
 *
 * @package spec\Digbang\L4Backoffice\Actions
 * @mixin \Digbang\L4Backoffice\Actions\ActionFactory
 */
class ActionFactorySpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$this->beConstructedWith(new ControlFactory($viewFactory->getWrappedObject()));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\ActionFactory');
    }

	function it_should_create_link_actions()
	{
		$this->link('http://google.com')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Action');
		$this->link('http://google.com', 'Google')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Action');
		$this->link('http://google.com', 'Google', ['class' => 'btn btn-primary'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Action');
	}

	function it_should_create_form_actions()
	{
		$this->form('/', 'Press my buttons!')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Action');
		$this->form('/', 'Press my buttons!', ['class' => 'btn btn-default'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Action');
	}

	function it_should_create_modal_actions_with_a_form(Form $form)
	{
		$this->modal($form, 'aLabel', ['some' => 'options'], 'anIcon')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Modal');
	}

	function it_should_create_modal_actions_with_a_closure(Form $form)
	{
		$this->modal(function($row){
				return $form;
			}, 'aLabel', ['some' => 'options'], 'anIcon')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Modal');
	}
}

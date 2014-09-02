<?php namespace spec\Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Actions\ActionFactory as ActionFactory;
use Digbang\L4Backoffice\Inputs\InputFactory as InputFactory;
use Illuminate\Session\Store;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class FormSpec
 * @mixin \Digbang\L4Backoffice\Forms\Form
 * @package spec\Digbang\L4Backoffice\Forms
 */
class FormSpec extends ObjectBehavior
{
	function let(Store $session, Factory $viewFactory)
	{
		/* @var $session \PhpSpec\Wrapper\Collaborator */
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
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

	function it_should_let_me_access_values()
	{
		$inputs = $this->inputs();

		$inputs->text('aName', 'A Label');

		/* @var $aName \Digbang\L4Backoffice\Inputs\Input */
		$aName = $inputs->find('aName');
		$aName->setValue('aName', $value = uniqid());

		$this->value('aName')->shouldReturn($value);
	}

	function it_should_let_me_fill_the_inputs_with_values()
	{
		$this->inputs()
			->text('aName', 'A Label')
			->text('aName2', 'A Label')
			->text('aName3', 'A Label');

		$this->fill([
			'aName' => 'aValue',
			'aName2' => 'another Value',
			'aName3' => 34
		]);

		$this->value('aName')->shouldBe('aValue');
		$this->value('aName2')->shouldBe('another Value');
		$this->value('aName3')->shouldBe(34);
	}
}

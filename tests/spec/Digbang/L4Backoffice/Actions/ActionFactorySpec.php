<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionFactorySpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new ControlFactory());
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
}

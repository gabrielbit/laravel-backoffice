<?php namespace spec\Digbang\L4Backoffice\Actions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Factory');
    }

	function it_should_create_link_actions()
	{
		$this->link('http://google.com')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
		$this->link('http://google.com', 'Google')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
		$this->link('http://google.com', 'Google', ['class' => 'btn btn-primary'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Link');
	}

	function it_should_create_form_actions()
	{
		$this->form('/', 'Press my buttons!')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Form');
		$this->form('/', 'Press my buttons!', ['class' => 'btn btn-default'])->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Form');
	}
}

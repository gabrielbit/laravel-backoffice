<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Actions\Factory;
use Digbang\L4Backoffice\Actions\Form;
use Illuminate\View\Environment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior
{
	function let(Factory $factory)
	{
		$this->beConstructedWith($factory);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\Collection');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Digbang\L4Backoffice\Support\Collection');
	}


}

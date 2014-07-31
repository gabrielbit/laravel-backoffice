<?php namespace spec\Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Digbang\L4Backoffice\Support\Collection;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CollectionSpec
 * @mixin \Digbang\L4Backoffice\Inputs\Collection
 * @package spec\Digbang\L4Backoffice\Inputs
 */
class CollectionSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$this->beConstructedWith(new InputFactory(new ControlFactory($viewFactory->getWrappedObject())), new Collection());
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Inputs\Collection');
    }

	function it_should_find_a_filter_by_its_name()
	{
		$this->find('someName')->shouldBe(null);

		$this->text('someName');

		$this->find('someName')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\InputInterface');
	}

	function it_should_give_me_a_collection_of_filters()
	{
		$this->text('someName1');
		$this->text('someName2');
		$this->text('someName3');
		$this->text('someName4');

		$this->all()->shouldBeAnInstanceOf('\Illuminate\Support\Collection');
		$this->all()->count()->shouldBe(4);
	}

	function it_should_be_traversable()
	{
		$this->shouldHaveType('Traversable');
	}
}

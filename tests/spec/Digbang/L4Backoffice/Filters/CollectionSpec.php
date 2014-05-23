<?php namespace spec\Digbang\L4Backoffice\Filters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\Collection');
    }

	function it_should_create_text_filters()
	{
		$this->text('some_name', 'Some Label', ['some' => 'options'])->shouldReturn($this);

		$this->count()->shouldBe(1);

		$this->find('some_name')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Text');
	}

	function it_should_create_dropdown_filters()
	{
		$this->dropdown('some_name', 'Some Label', [['option1' => 'Label for it'], ['optionN' => 'You get it']])
			->shouldReturn($this);

		$this->count()->shouldBe(1);
	}

	function it_should_find_a_filter_by_its_name()
	{
		$this->find('someName')->shouldBe(null);

		$this
			->text('someName')
			->find('someName')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\FilterInterface');
	}
}

<?php namespace spec\Digbang\L4Backoffice\Listings;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ColumnSpec
 * @mixin \Digbang\L4Backoffice\Listings\Column
 * @package spec\Digbang\L4Backoffice
 */
class ColumnSpec extends ObjectBehavior
{
	private $id;

	function let()
	{
		$this->beConstructedWith($this->id = uniqid(), "some label for {$this->id}", (boolean) rand(0, 1));
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listings\Column');
    }

	function it_can_be_sortable()
	{
		$this->setSortable(true);

		$this->sortable()->shouldReturn(true);
	}

	function it_can_have_a_closure_as_an_accesor()
	{
		$this->setAccessor(function(array $row){
			return $row['crazy_stuff'];
		});

		$this->getValue(
			[
				'crazy_stuff' => 'Some Value',
				'you shouldnt' => 'See this...'
			]
		)->shouldReturn('Some Value');
	}

	function it_can_have_an_accesor_with_the_same_name_as_a_php_function()
	{
		$this->setAccessor('hash');

		$this->getValue(['hash' => 'Some Value'])->shouldReturn('Some Value');
	}
}

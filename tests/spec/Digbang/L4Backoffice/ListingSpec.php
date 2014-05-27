<?php namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\ColumnCollection;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Digbang\L4Backoffice\Filters\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Digbang\L4Backoffice\Listing
 * @package spec\Digbang\L4Backoffice
 */
class ListingSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith(new FilterCollection(new Factory()));

		$columns = new ColumnCollection(['name' => 'Name', 'address' => 'Address', 'zip_code' => 'Zip Code']);

		$this->columns($columns);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listing');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Digbang\L4Backoffice\Support\Collection');
	}

	function it_should_have_a_filters_collection()
	{
		$this->filters()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Collection');
	}

	function it_should_give_me_a_specific_filter_by_name()
	{
		$this->filters()->text('some_filter');

		$this->filters('some_filter')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Filters\Text');
	}

	function it_should_let_me_fill_it_with_a_well_formed_array()
	{
		$this->count()->shouldBe(0);

		$this->fill([
			['name' => 'Some name', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'Other name', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'More names', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'etc.', 'address' => 'Some Address', 'zip_code' => '12345']
		]);

		$this->count()->shouldBe(4);
	}

	function it_should_throw_an_error_when_filling_with_a_malformed_array()
	{
		$this->shouldThrow('\InvalidArgumentException')
			->duringFill([
			['name' => 'Some name', 'address' => 'Some Address', 'value' => 'not important'],
			['name' => 'Other name', 'zip_code' => '12345', 'value' => 'not important'],
			['address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'etc.', 'address' => 'Some Address', 'zip_code' => '12345']
		]);
	}

	function it_should_let_me_fill_it_with_a_collection_element()
	{
		$this->count()->shouldBe(0);

		$this->fill(new \Illuminate\Support\Collection([
			['name' => 'Some name', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'Other name', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'More names', 'address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
			['name' => 'etc.', 'address' => 'Some Address', 'zip_code' => '12345']
		]));

		$this->count()->shouldBe(4);
	}

	function it_should_also_fail_when_filling_with_a_malformed_collection_element()
	{
		$this->shouldThrow('\InvalidArgumentException')
			->duringFill(new \Illuminate\Support\Collection([
				['name' => 'Some name', 'address' => 'Some Address', 'value' => 'not important'],
				['name' => 'Other name', 'zip_code' => '12345', 'value' => 'not important'],
				['address' => 'Some Address', 'zip_code' => '12345', 'value' => 'not important'],
				['name' => 'etc.', 'address' => 'Some Address', 'zip_code' => '12345']
			]));
	}
}

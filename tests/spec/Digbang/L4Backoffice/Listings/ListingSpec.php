<?php namespace spec\Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory as FilterFactory;
use Digbang\L4Backoffice\Listings\ColumnCollection;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Digbang\L4Backoffice\Listings\Listing
 * @package spec\Digbang\L4Backoffice
 */
class ListingSpec extends ObjectBehavior
{
	function let()
	{
		$controlFactory = new ControlFactory();
		$filterFactory  = new FilterFactory($controlFactory);

		$this->beConstructedWith(
			new DigbangCollection(),
			$filterFactory->collection()
		);

		$columns = new ColumnCollection(['name' => 'Name', 'address' => 'Address', 'zip_code' => 'Zip Code']);

		$this->columns($columns);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Listings\Listing');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
	    $this->shouldHaveType('\Countable');
    }

	function it_should_have_a_filters_collection()
	{
		$this->filters()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Collection');
	}

	function it_should_give_me_a_specific_filter_by_name()
	{
		$this->filters()->text('some_filter');

		$this->filters('some_filter')->shouldBeAnInstanceOf('Digbang\L4Backoffice\Inputs\Input');
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

	function it_should_hold_actions_that_may_apply_to_the_listing_or_just_link_somewhere_related()
	{
		$actionFactory = new \Digbang\L4Backoffice\Actions\Factory(new ControlFactory());

		$this->setActions($actionFactory->collection());

		$this->actions()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Collection');
	}

	function it_should_hold_row_actions_for_each_element_in_the_listing()
	{
		$actionFactory = new \Digbang\L4Backoffice\Actions\Factory(new ControlFactory());

		$this->setRowActions($actionFactory->collection());

		$this->rowActions()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Collection');
	}

	function it_should_hold_bulk_actions_that_apply_to_selected_items()
	{
		$actionFactory = new \Digbang\L4Backoffice\Actions\Factory(new ControlFactory());

		$this->setBulkActions($actionFactory->collection());

		$this->bulkActions()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Actions\Collection');
	}
}

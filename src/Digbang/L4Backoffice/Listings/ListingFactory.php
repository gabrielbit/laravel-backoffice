<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Inputs\Factory as InputFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class ListingFactory
{
	protected $InputFactory;
	protected $actionFactory;

	public function __construct(InputFactory $InputFactory, ActionFactory $actionFactory)
	{
		$this->InputFactory = $InputFactory;
		$this->actionFactory = $actionFactory;
	}

    public function make(ColumnCollection $columns)
    {
        $listing = new Listing(
	        new DigbangCollection(),
	        $this->InputFactory->collection(),
	        $this->actionFactory->collection()
        );

	    $listing->columns($columns);

	    return $listing;
    }
}

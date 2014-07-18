<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Inputs\InputFactory as InputFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class ListingFactory
{
	protected $InputFactory;

	public function __construct(InputFactory $InputFactory)
	{
		$this->InputFactory = $InputFactory;
	}

    public function make(ColumnCollection $columns)
    {
        $listing = new Listing(
	        new DigbangCollection(),
	        $this->InputFactory->collection()
        );

	    $listing->columns($columns);

	    return $listing;
    }
}

<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Filters\Factory as FilterFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class ListingFactory
{
	protected $filterFactory;

	public function __construct(FilterFactory $filterFactory)
	{
		$this->filterFactory = $filterFactory;
	}

    public function make()
    {
        return new Listing(
	        new DigbangCollection(),
	        $this->filterFactory->collection()
        );
    }
}

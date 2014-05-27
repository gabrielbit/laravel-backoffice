<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Filters\Factory as FilterFactory;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;

class ListingFactory
{
	protected $filterFactory;

	public function __construct(FilterFactory $filterFactory)
	{
		$this->filterFactory = $filterFactory;
	}

    public function make()
    {
        return new Listing(new FilterCollection($this->filterFactory));
    }
}

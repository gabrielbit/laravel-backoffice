<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Filters\Factory as FilterFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class ListingFactory
{
	protected $filterFactory;
	protected $actionFactory;

	public function __construct(FilterFactory $filterFactory, ActionFactory $actionFactory)
	{
		$this->filterFactory = $filterFactory;
		$this->actionFactory = $actionFactory;
	}

    public function make()
    {
        return new Listing(
	        new DigbangCollection(),
	        $this->filterFactory->collection(),
	        $this->actionFactory->collection()
        );
    }
}

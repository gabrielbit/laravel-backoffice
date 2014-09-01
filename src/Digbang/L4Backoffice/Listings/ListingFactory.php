<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Extractors\ValueExtractorFacade;
use Digbang\L4Backoffice\Inputs\InputFactory as InputFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class ListingFactory
{
	protected $inputFactory;
	protected $viewFactory;
	protected $controlFactory;

	public function __construct(InputFactory $inputFactory, ControlFactory $controlFactory)
	{
		$this->inputFactory = $inputFactory;
		$this->controlFactory = $controlFactory;
	}

    public function make(ColumnCollection $columns)
    {
        $listing = new Listing(
	        $this->controlFactory->make('l4-backoffice::listing', ''),
	        new DigbangCollection(),
	        $this->inputFactory->collection(),
	        new ValueExtractorFacade()
        );

	    $listing->columns($columns);

	    return $listing;
    }
}

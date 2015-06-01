<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Extractors\ValueExtractorFacade;
use Digbang\L4Backoffice\Inputs\InputFactory as InputFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Http\Request;

class ListingFactory
{
	/**
	 * @type InputFactory
	 */
	protected $inputFactory;

	/**
	 * @type ControlFactory
	 */
	protected $controlFactory;

	/**
	 * @type Request
	 */
	private $request;

	/**
	 * @param InputFactory   $inputFactory
	 * @param ControlFactory $controlFactory
	 * @param Request        $request
	 */
	public function __construct(InputFactory $inputFactory, ControlFactory $controlFactory, Request $request = null)
	{
		$this->inputFactory = $inputFactory;
		$this->controlFactory = $controlFactory;
		$this->request = $request;
	}

	/**
	 * @param ColumnCollection $columns
	 * @return Listing
	 */
    public function make(ColumnCollection $columns)
    {
        $listing = new Listing(
	        $this->controlFactory->make('l4-backoffice::listing', ''),
	        new DigbangCollection(),
	        $this->inputFactory->collection(),
	        new ValueExtractorFacade(),
	        $this->request
        );

	    $listing->columns($columns);

	    return $listing;
    }
}

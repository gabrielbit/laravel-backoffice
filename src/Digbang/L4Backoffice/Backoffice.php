<?php namespace Digbang\L4Backoffice;

class Backoffice
{
	protected $listingFactory;

	public function __construct(ListingFactory $listingFactory)
	{
		$this->listingFactory = $listingFactory;
	}

    public function listing()
    {
        return $this->listingFactory->make();
    }

    public function breadcrumb($data = [])
    {
        $breadcrumb = new Breadcrumb();

	    return $breadcrumb->mergeInto($data);
    }
}

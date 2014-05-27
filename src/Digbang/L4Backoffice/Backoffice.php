<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;

class Backoffice
{
	protected $listingFactory;
	protected $actionFactory;

	public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory)
	{
		$this->listingFactory = $listingFactory;
		$this->actionFactory = $actionFactory;
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

    public function columns($data = [])
    {
        $columns = new ColumnCollection();

	    foreach ($data as $id => $label)
	    {
		    $columns->push(new Column($id, $label));
	    }

	    return $columns;
    }

    public function actions()
    {
        return new ActionCollection($this->actionFactory);
    }
}

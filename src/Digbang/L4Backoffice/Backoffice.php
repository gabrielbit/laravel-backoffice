<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Support\Breadcrumb;
use Illuminate\Support\Facades\Lang;

class Backoffice
{
	protected $listingFactory;
	protected $actionFactory;
	protected $controlFactory;

	public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory, ControlFactory $controlFactory)
	{
		$this->listingFactory = $listingFactory;
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
	}

    public function listing()
    {
        return $this->listingFactory->make();
    }

    public function breadcrumb($data = [], $label = '', $options = [])
    {
        return new Breadcrumb(
	        $this->controlFactory->make('l4-backoffice::breadcrumb', $label, $options),
	        new \Illuminate\Support\Collection($data)
        );
    }

    public function columns($data = [])
    {
        $columns = new ColumnCollection();

	    foreach ($data as $id => $label)
	    {
		    $columns->push(new Column(is_string($id) ? $id : $label, $label));
	    }

	    return $columns;
    }

    public function actions()
    {
        return new ActionCollection($this->actionFactory);
    }
}

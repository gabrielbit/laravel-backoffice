<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Forms\Factory as FormFactory;
use Digbang\L4Backoffice\Listings\Column;
use Digbang\L4Backoffice\Listings\ColumnCollection;
use Digbang\L4Backoffice\Listings\ListingFactory;
use Digbang\L4Backoffice\Support\Breadcrumb;

class Backoffice
{
	protected $listingFactory;
	protected $actionFactory;
	protected $controlFactory;
	protected $formFactory;

	public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory, ControlFactory $controlFactory, FormFactory $formFactory)
	{
		$this->listingFactory = $listingFactory;
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
		$this->formFactory = $formFactory;
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
        return $this->actionFactory->collection();
    }

    public function form()
    {
	    return $this->formFactory->make();
    }
}

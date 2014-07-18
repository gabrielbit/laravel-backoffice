<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Forms\FormFactory;
use Digbang\L4Backoffice\Listings\ColumnFactory;
use Digbang\L4Backoffice\Listings\ListingFactory;
use Digbang\L4Backoffice\Support\Breadcrumb;

class Backoffice
{
	protected $listingFactory;
	protected $actionFactory;
	protected $controlFactory;
	protected $formFactory;
	protected $columnFactory;

	public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory, ControlFactory $controlFactory, FormFactory $formFactory, ColumnFactory $columnFactory)
	{
		$this->listingFactory = $listingFactory;
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
		$this->formFactory    = $formFactory;
		$this->columnFactory  = $columnFactory;
	}

    public function listing($columns = [])
    {
        return $this->listingFactory->make(
	        $this->columnFactory->make($columns)
        );
    }

    public function breadcrumb($data = [], $label = '', $options = [])
    {
        return new Breadcrumb(
	        $this->controlFactory->make('l4-backoffice::breadcrumb', $label, $options),
	        new \Illuminate\Support\Collection($data)
        );
    }

    public function actions()
    {
        return $this->actionFactory->collection();
    }

    public function form($target, $label, $method = 'POST', $cancelAction = '', $options = [])
    {
	    return $this->formFactory->make($target, $label, $method, $cancelAction, $options);
    }
}

<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Forms\FormFactory;
use Digbang\L4Backoffice\Listings\ColumnFactory;
use Digbang\L4Backoffice\Listings\ListingFactory;
use Digbang\L4Backoffice\Support\Breadcrumb;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Urls\SecureUrl;

class Backoffice
{
	protected $listingFactory;
	protected $actionFactory;
	protected $controlFactory;
	protected $formFactory;
	protected $columnFactory;
	protected $secureUrl;

	public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory, ControlFactory $controlFactory, FormFactory $formFactory, ColumnFactory $columnFactory, SecureUrl $secureUrl)
	{
		$this->listingFactory = $listingFactory;
		$this->actionFactory  = $actionFactory;
		$this->controlFactory = $controlFactory;
		$this->formFactory    = $formFactory;
		$this->columnFactory  = $columnFactory;
		$this->secureUrl = $secureUrl;
	}

    public function listing($columns = [])
    {
        return $this->listingFactory->make(
	        $this->columnFactory->make($columns)
        );
    }

    public function breadcrumb($data = [], $label = '', $options = [])
    {
	    $current = array_pop($data);

	    try
	    {
		    $routes = [];
		    foreach ($data as $text => $route)
		    {
			    if (! is_string($text))
			    {
				    $routes[] =  $route;
			    }
			    else
			    {
				    if (! is_string($route) || strpos($route, '//') === false)
				    {
					    $route = call_user_func_array([$this->secureUrl, 'route'], (array) $route);
				    }

				    $routes[$text] = $route;
			    }
		    }

		    $routes[] = $current;

		    return new Breadcrumb(
			    $this->controlFactory->make('l4-backoffice::breadcrumb', $label, $options),
			    new \Illuminate\Support\Collection($routes)
	        );
	    }
	    catch (PermissionException $e)
	    {
		    // Discard the first one
		    array_shift($data);

		    return call_user_func_array([$this, 'breadcrumb'], $data + [$current]);
	    }
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

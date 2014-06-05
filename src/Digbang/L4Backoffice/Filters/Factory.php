<?php namespace Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Http\Request;

class Factory
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlFactory
	 */
	protected $controlFactory;
	protected $request;

	function __construct(ControlFactory $controlFactory, Request $request = null)
	{
		$this->controlFactory = $controlFactory;
		$this->request = $request;
	}

	public function text($name, $label = null, $options = [])
    {
	    return new Filter(
		    $this->controlFactory->make(
			    'l4-backoffice::filters.text',
			    null,
		        $this->buildOptions($options, $label)),
		    $name,
		    $this->request ? $this->request->get($name) : null
	    );
    }

	public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    return new DropDown(
		    $this->controlFactory->make(
			    'l4-backoffice::filters.dropdown',
			    null,
			    $this->buildOptions($options, $label)),
		    $name,
		    $this->request ? $this->request->get($name) : null,
		    $data
	    );
    }

	public function collection()
	{
		return new Collection($this, new DigbangCollection());
	}

	protected function buildOptions($options, $label)
	{
		if (!$label)
		{
			return $options;
		}

		return array_add($options, 'placeholder', $label);
	}
}

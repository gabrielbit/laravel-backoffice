<?php namespace Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class Factory
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlFactory
	 */
	protected $controlFactory;

	function __construct(ControlFactory $controlFactory)
	{
		$this->controlFactory = $controlFactory;
	}

	public function text($name, $label = null, $options = [])
    {
	    return new Filter(
		    $this->controlFactory->make(
			    'l4-backoffice::filters.text',
			    null,
		        $this->buildOptions($options, $label)),
		    $name,
		    \Input::get($name)
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
		    \Input::get($name),
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

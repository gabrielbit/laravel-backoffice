<?php namespace Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Controls\ControlInterface;
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
	    return $this->make(
		    'l4-backoffice::inputs.text',
		    $label,
		    $this->buildOptions($options, $label),
		    $name
	    );
    }

	public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    return new DropDown(
		    $this->controlFactory->make(
			    'l4-backoffice::inputs.dropdown',
			    null,
			    $this->buildOptions($options, $label)),
		    $name,
		    $this->request ? $this->request->get($name) : null,
		    $data
	    );
    }

	public function button($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.button',
			$label,
			$options,
			$name
		);
	}

	public function checkbox($name, $label, $options = [])
	{
		return $this->make('l4-backoffice::inputs.checkbox', $label, $options, $name);
	}

	public function collection()
	{
		return new Collection($this, new DigbangCollection());
	}

	protected function make($view, $label, $options, $name)
	{
		return new Input(
			$this->controlFactory->make(
				$view,
				$label,
				$options
			),
			$name,
			$this->request ? $this->request->get($name) : null
		);
	}

	protected function buildOptions($options, $label)
	{
		$options = array_add($options, 'class', 'form-control');

		if (!$label)
		{
			return $options;
		}

		return array_add($options, 'placeholder', $label);
	}
}

<?php namespace Digbang\L4Backoffice\Controls;

use Illuminate\View\Factory;

class ControlFactory
{
	protected $viewFactory;

	function __construct(Factory $viewFactory)
	{
		$this->viewFactory = $viewFactory;
	}

	public function make($view, $label, $options = [])
	{
		$control = new Control($this->viewFactory);

		$control->setView($view);
		$control->setLabel($label);
		$control->setOptions($options);

		return $control;
	}
} 
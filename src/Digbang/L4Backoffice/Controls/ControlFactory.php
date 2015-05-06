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
		return new Control(
			$this->viewFactory,
			$view,
			$label,
			$options
		);
	}
} 
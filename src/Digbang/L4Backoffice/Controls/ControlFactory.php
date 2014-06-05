<?php namespace Digbang\L4Backoffice\Controls;

class ControlFactory
{
	public function make($view, $label, $options = [])
	{
		$control = new Control();

		$control->setView($view);
		$control->setLabel($label);
		$control->setOptions($options);

		return $control;
	}
} 
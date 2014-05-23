<?php namespace Digbang\L4Backoffice\Controls;

/**
 * Trait ControlTrait
 * @package Digbang\L4Backoffice\Controls
 */
trait ControlTrait
{
	protected $options;
	protected $label;
	protected $view;

	/**
	 * @param mixed $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function label()
	{
		return $this->label;
	}

	/**
	 * @param mixed $options
	 */
	public function setOptions($options)
	{
		if (!$options instanceof \Illuminate\Support\Collection)
		{
			$options = new \Illuminate\Support\Collection($options);
		}

		$this->options = $options;
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function options($name = null)
	{
		if (!$name)
		{
			return $this->options;
		}

		return $this->options[$name];
	}

	public function setView($view)
	{
		$this->view = $view;
	}

	public function view()
	{
		return $this->view;
	}
} 
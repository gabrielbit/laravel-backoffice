<?php namespace Digbang\L4Backoffice\Controls;

use Illuminate\Support\Collection;
use Illuminate\View\Factory;

/**
 * Immutable implementation of the Control class.
 * @package Digbang\L4Backoffice\Controls
 * @implements ControlInterface
 */
final class Control implements ControlInterface
{
	/**
	 * @var \Illuminate\View\Factory
	 */
	private $viewFactory;

	/**
	 * @type string
	 */
	private $view;

	/**
	 * @type string
	 */
	private $label;

	/**
	 * @type array
	 */
	private $options;

	/**
	 * @param Factory          $viewFactory
	 * @param string           $view
	 * @param string           $label
	 * @param array|Collection $options
	 */
	public function __construct(Factory $viewFactory, $view, $label, $options = [])
	{
		if (!$options instanceof Collection)
		{
			$options = new Collection($options);
		}

		$this->viewFactory = $viewFactory;
		$this->view        = $view;
		$this->label       = $label;
		$this->options     = $options;
	}

	public function view()
	{
		return $this->view;
	}

	public function label()
	{
		return $this->label;
	}

	public function option($key)
	{
		return $this->options->get($key, null);
	}

	public function options()
	{
		return $this->options->all();
	}

	public function hasClass($className)
	{
		$classes = $this->option('class');

		return strpos($classes, $className) !== false;
	}

	public function changeView($view)
	{
		return new self(
			$this->viewFactory,
			$view,
			$this->label,
			$this->options
		);
	}

	public function changeLabel($label)
	{
		return new self(
			$this->viewFactory,
			$this->view,
			$label,
			$this->options
		);
	}

	public function changeOptions($options)
	{
		return new self(
			$this->viewFactory,
			$this->view,
			$this->label,
			$options
		);
	}

	public function changeOption($key, $value)
	{
		$options = $this->options->all();
		$options[$key] = $value;

		return $this->changeOptions($options);
	}

	public function render()
	{
		return $this->viewFactory->make($this->view(), [
			'options' => $this->options(),
			'label'   => $this->label(),
		]);
	}

	/**
	 * Clone options so they are separated from the cloned control
	 */
	public function __clone()
	{
		$this->options = clone $this->options;
	}
}

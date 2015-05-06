<?php namespace Digbang\L4Backoffice\Controls;

trait ControlWrapperTrait
{
	/**
	 * @type ControlInterface
	 */
	protected $control;

	public function view()
	{
		return $this->control->view();
	}

	public function label()
	{
		return $this->control->label();
	}

	public function option($key)
	{
		return $this->control->option($key, null);
	}

	public function options()
	{
		return $this->control->options();
	}

	public function hasClass($className)
	{
		return $this->control->hasClass($className);
	}

	public function changeView($view)
	{
		$this->control = $this->control->changeView($view);
	}

	public function changeLabel($label)
	{
		$this->control = $this->control->changeLabel($label);
	}

	public function changeOptions($options)
	{
		$this->control = $this->control->changeOptions($options);
	}

	public function changeOption($key, $value)
	{
		$this->control = $this->control->changeOption($key, $value);
	}
}

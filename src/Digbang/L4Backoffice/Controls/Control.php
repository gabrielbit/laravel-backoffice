<?php namespace Digbang\L4Backoffice\Controls;

class Control implements ControlInterface
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

		return array_get($this->options, $name, null);
	}

	public function setView($view)
	{
		$this->view = $view;
	}

	public function view()
	{
		return $this->view;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return \Illuminate\View\View
	 */
	public function render()
	{
		return \View::make($this->view(), [
			'options' => $this->options()->toArray(),
			'label'   => $this->label(),
		]);
	}
}

<?php namespace Digbang\L4Backoffice\Filters;

/**
 * Class FilterTrait
 * @package Digbang\L4Backoffice\Filters
 */
trait FilterTrait
{
	protected $name;
	protected $label;
	protected $value;
	protected $options;
	protected $defaultsTo;

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
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function name()
	{
		return $this->name;
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

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function value()
	{
		return $this->value;
	}

	public function defaultsTo($value)
	{
		$this->defaultsTo = $value;
	}
}
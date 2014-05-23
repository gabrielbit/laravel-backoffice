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
		$this->options = $options;
	}

	/**
	 * @return mixed
	 */
	public function options()
	{
		return $this->options;
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
}
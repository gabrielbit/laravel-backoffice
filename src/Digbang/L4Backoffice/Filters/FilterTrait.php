<?php namespace Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlTrait;

/**
 * Trait FilterTrait
 * @package Digbang\L4Backoffice\Filters
 */
trait FilterTrait
{
	use ControlTrait;

	protected $name;
	protected $value;
	protected $defaultsTo;

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
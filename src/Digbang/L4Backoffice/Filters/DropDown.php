<?php namespace Digbang\L4Backoffice\Filters;

/**
 * Class DropDown
 * @package Digbang\L4Backoffice\Filters
 */
class DropDown implements FilterInterface
{
	use FilterTrait;

	protected $data;

	function __construct($name, $label = '', $data = [], $options = [])
	{
		$this->name = $name;
		$this->label = $label;
		$this->data = $data;
		$this->options = $options;
	}


	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return mixed
	 */
	public function data()
	{
		return $this->data;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		// TODO: Implement render() method.
	}
}
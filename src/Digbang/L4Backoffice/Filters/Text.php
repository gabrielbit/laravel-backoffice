<?php namespace Digbang\L4Backoffice\Filters;

class Text implements FilterInterface
{
	use FilterTrait;

	function __construct($name, $label = '', $options = [])
	{
		$this->name = $name;
		$this->label = $label;
		$this->options = $options;
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

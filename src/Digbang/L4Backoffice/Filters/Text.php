<?php namespace Digbang\L4Backoffice\Filters;

class Text implements FilterInterface
{
	use FilterTrait;

	function __construct()
	{
		$this->view = 'l4-backoffice::filters.text';
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return \View::make($this->view(), [
			'name'    => $this->name(),
			'label'   => $this->label(),
			'value'   => $this->value(),
			'options' => $this->options()
		])->render();
	}
}

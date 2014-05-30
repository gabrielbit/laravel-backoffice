<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Controls\ControlTrait;

class Breadcrumb extends Collection implements ControlInterface
{
	use ControlTrait;

	public function __construct(array $items = array())
	{
		parent::__construct($items);

		$this->view = 'l4-backoffice::breadcrumb';
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return \View::make($this->view, [
			'items'   => $this->items,
			'label'   => $this->label(),
			'options' => $this->options()
		])->render();
	}
}

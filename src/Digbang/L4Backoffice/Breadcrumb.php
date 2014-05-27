<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Controls\ControlTrait;
use Digbang\L4Backoffice\Support\Collection;

class Breadcrumb extends Collection implements ControlInterface
{
	use ControlTrait;

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

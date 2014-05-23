<?php namespace Digbang\L4Backoffice\Actions;

class Link implements ActionInterface
{
	use ActionTrait;

	function __construct()
	{
		$this->view = 'l4-backoffice::link';
	}

	public function render()
	{
		return \View::make($this->view(), [
			'target'  => $this->target(),
			'label'   => $this->label(),
			'options' => $this->options()
		])->render();
	}
}

<?php namespace Digbang\L4Backoffice\Actions;

class Form implements ActionInterface
{
	use ActionTrait;

	function __construct()
	{
		$this->view = 'l4-backoffice::form';
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

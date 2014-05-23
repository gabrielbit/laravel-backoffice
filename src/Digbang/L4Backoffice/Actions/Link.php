<?php namespace Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment as ViewFactory;

class Link implements ActionInterface
{
	use ActionTrait;

	protected $viewFactory;

	function __construct(ViewFactory $viewFactory)
	{
		$this->viewFactory = $viewFactory;
		$this->view = 'l4-backoffice::link';
	}

	public function render()
	{
		return $this->viewFactory->make($this->view, [
			'target'  => $this->target(),
			'label'   => $this->label(),
			'options' => $this->options()
		])->render();
	}
}
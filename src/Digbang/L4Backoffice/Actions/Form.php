<?php namespace Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment as ViewFactory;

class Form implements ActionInterface
{
	use ActionTrait;

	protected $viewFactory;

	function __construct(ViewFactory $viewFactory)
	{
		$this->viewFactory = $viewFactory;
		$this->view = 'l4-backoffice::form';
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		$this->viewFactory->make($this->view(), [
			'target'  => $this->target(),
			'label'   => $this->label(),
			'options' => $this->options()
		])->render();
	}
}

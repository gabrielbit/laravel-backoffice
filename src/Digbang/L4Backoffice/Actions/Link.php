<?php namespace Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment as ViewFactory;

class Link implements ActionInterface
{
	use ActionTrait;

	protected $view = 'l4-backoffice::link';
	protected $viewFactory;

	function __construct(ViewFactory $viewFactory)
	{
		$this->viewFactory = $viewFactory;
	}

	/**
	 * @param mixed $view
	 */
	public function setView($view)
	{
		$this->view = $view;
	}

	/**
	 * @return mixed
	 */
	public function view()
	{
		return $this->view;
	}

	public function render()
	{
		return $this->viewFactory->make($this->view, [
			'target'  => $this->target,
			'label'   => $this->label,
			'options' => $this->options
		])->render();
	}
}

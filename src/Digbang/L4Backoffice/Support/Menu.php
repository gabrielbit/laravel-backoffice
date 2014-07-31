<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Contracts\RenderableInterface;

class Menu implements RenderableInterface
{
	protected $actionTree;
	protected $control;

	function __construct(ControlInterface $control, ActionCollection $actionTree)
	{
		$this->control = $control;
		$this->actionTree = $actionTree;
	}

	/**
	 * @param \Digbang\L4Backoffice\Actions\Collection $actionTree
	 */
	public function setActionTree(ActionCollection $actionTree)
	{
		$this->actionTree = $actionTree;
	}

	/**
	 * @return mixed
	 */
	public function getActionTree()
	{
		return $this->actionTree;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->control->render()->with([
			'actionTree' => $this->actionTree
		]);
	}
}

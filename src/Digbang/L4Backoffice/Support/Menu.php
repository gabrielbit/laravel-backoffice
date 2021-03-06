<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Contracts\RenderableInterface;

class Menu implements RenderableInterface, \Countable
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
	 * @return ActionCollection
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

	public function count()
	{
		$count = 0;

		foreach ($this->actionTree as $action)
		{
			if ($action instanceof ActionCollection)
			{
				$count += $action->count();
			}
			else
			{
				$count++;
			}
		}

		return $count;
	}

	public function isEmpty()
	{
		return $this->count() == 0;
	}
}

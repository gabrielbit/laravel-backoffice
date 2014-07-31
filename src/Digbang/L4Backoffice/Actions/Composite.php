<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Contracts\RenderableInterface;

class Composite extends Collection implements ActionInterface, RenderableInterface
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlInterface
	 */
	protected $control;
	protected $icon;

	function __construct(ControlInterface $control, ActionFactory $factory, \Illuminate\Support\Collection $collection, $icon = null)
	{
		parent::__construct($factory, $collection);

		$this->control = $control;
		$this->icon    = $icon;
	}

	/**
	 * The target URL this action points to.
	 * @return string
	 */
	public function target()
	{
		// Composite actions don't use target, they trigger a javascript drop-down effect
		return '#';
	}

	/**
	 * @param string $icon
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;
	}

	/**
	 * @return string
	 */
	public function icon()
	{
		return $this->icon;
	}

	public function isActive()
	{
		foreach ($this->collection as $action)
		{
			/* @var $action ActionInterface */
			if ($action->isActive())
			{
				return true;
			}
		}

		return false;
	}

	public function render()
	{
		return $this->control->render()->with([
			'target'   => $this->target(),
			'actions'  => $this->collection,
			'icon'     => $this->icon(),
			'isActive' => $this->isActive()
		]);
	}
}
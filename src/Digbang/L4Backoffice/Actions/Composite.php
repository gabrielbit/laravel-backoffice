<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Contracts\RenderableInterface;

class Composite extends Collection implements ActionInterface, RenderableInterface
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlInterface
	 */
	protected $control;

	function __construct(ControlInterface $control, ActionFactory $factory, \Illuminate\Support\Collection $collection)
	{
		parent::__construct($factory, $collection);

		$this->control = $control;
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

	public function render()
	{
		return $this->control->render()->with([
			'actions' => $this->collection
		]);
	}
}
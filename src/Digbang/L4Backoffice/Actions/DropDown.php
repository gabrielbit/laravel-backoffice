<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Contracts\RenderableInterface;

class DropDown extends Collection implements RenderableInterface
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlInterface
	 */
	protected $control;

	function __construct(ControlInterface $control, Factory $factory, \Illuminate\Support\Collection $collection)
	{
		parent::__construct($factory, $collection);

		$this->control = $control;
	}

	public function render()
	{
		return $this->control->render()->with([
			'actions' => $this->collection
		]);
	}
}
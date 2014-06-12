<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Inputs\Collection;
use Illuminate\Support\Contracts\RenderableInterface;

class Form implements RenderableInterface
{
	protected $collection;
	protected $control;

	function __construct(ControlInterface $control, Collection $collection)
	{
		$this->control = $control;
		$this->collection = $collection;
	}

	public function inputs()
    {
        return $this->collection;
    }

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->control->render()->with([
			'inputs' => $this->collection
		]);
	}
}

<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Controls\ControlWrapperTrait;
use Illuminate\Support\Collection as LaravelCollection;

final class Breadcrumb implements ControlInterface
{
	use ControlWrapperTrait;

	private $collection;

	/**
	 * @param ControlInterface  $control
	 * @param LaravelCollection $collection
	 */
	public function __construct(ControlInterface $control, LaravelCollection $collection = null)
	{
		$this->control = $control;
		$this->collection = $collection;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->control->render()->with([
			'items' => $this->collection
		]);
	}
}

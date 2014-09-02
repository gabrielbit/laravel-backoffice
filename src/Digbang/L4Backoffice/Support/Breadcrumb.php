<?php namespace Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection as LaravelCollection;

class Breadcrumb implements ControlInterface
{
	protected $control;
	protected $collection;

	public function __construct(ControlInterface $control, LaravelCollection $collection = null)
	{
		$this->control = $control;
		if ($collection)
		{
			$this->collection = $collection;
		}
	}

	/**
	 * A text that will be printed to the user.
	 * @return string
	 */
	public function label()
	{
		return $this->control->label();
	}

	/**
	 * The control HTML options. May access a specific one through parameter, null should return all of them.
	 * @param string $name
	 * @return \Illuminate\Support\Collection
	 */
	public function options($name = null)
	{
		return $this->control->options($name);
	}

	/**
	 * The view that will be rendered. Controls always render a view of some sort.
	 * @return string
	 */
	public function view()
	{
		return $this->control->view();
	}

	/**
	 * Check if the given class name exists on the control
	 *
	 * @param $className
	 *
	 * @return boolean
	 */
	public function hasClass($className)
	{
		return $this->control->hasClass($className);
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

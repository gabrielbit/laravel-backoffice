<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;

class Action implements ActionInterface
{
	/**
	 * @var ControlInterface
	 */
	protected $control;
	protected $target;

	function __construct(ControlInterface $control, $target)
	{
		$this->control = $control;
		$this->target  = $target;
	}

	public function setTarget($target)
	{
		$this->target = $target;
	}

	public function target()
	{
		return $this->target;
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
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->control->render();
	}
}
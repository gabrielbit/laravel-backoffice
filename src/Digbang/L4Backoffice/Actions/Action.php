<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Illuminate\Support\Collection as LaravelCollection;

class Action implements ActionInterface, ControlInterface
{
	/**
	 * @var ControlInterface
	 */
	protected $control;
	protected $target;
	protected $icon;
	protected $isActive = false;

	function __construct(ControlInterface $control, $target, $icon = null)
	{
		$this->control = $control;
		$this->target  = $target;
		$this->icon = $icon;
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

	public function setActive($isActive)
	{
		$this->isActive = $isActive;
	}

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->isActive;
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

	public function render()
	{
		return $this->renderTarget($this->target());
	}

	public function renderWith($row)
	{
		$target = $this->target();

		if ($target instanceof \Closure)
		{
			try
			{
				$target = $target(new LaravelCollection($row));
			}
			catch (PermissionException $e)
			{
				// We'll assume that actions may be subject to permissions here
				$target = false;
			}

			if ($target === false)
			{
				// If the callback returns false, we don't render!
				return '';
			}
		}

		$options = $this->options();
		foreach ($options as $key => $option)
		{
			if ($option instanceof \Closure)
			{
				$options->put($key, $option(new LaravelCollection($row)));
			}
		}

		return $this->renderTarget($target);
	}

	protected function renderTarget($target)
	{
		return $this->control->render()->with([
			'target'   => $target,
			'icon'     => $this->icon(),
			'isActive' => $this->isActive()
		]);
	}
}
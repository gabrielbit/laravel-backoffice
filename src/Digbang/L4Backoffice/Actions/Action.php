<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Controls\ControlWrapperTrait;
use Digbang\L4Backoffice\Support\EvaluatorTrait;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Illuminate\Support\Collection as LaravelCollection;

class Action implements ActionInterface, ControlInterface
{
	use ControlWrapperTrait;
	use EvaluatorTrait;

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

	public function render()
	{
		return $this->renderTarget(
			$this->control,
			$this->target(),
			$this->icon(),
			$this->isActive()
		);
	}

	public function renderWith($row)
	{
		try
		{
			/** @type ControlInterface $control */
			$control = clone $this->control;

			$rowAsCollection = new LaravelCollection($row);

			$target   = $this->evaluate($this->target(),   $rowAsCollection);
			$icon     = $this->evaluate($this->icon(),     $rowAsCollection);
			$isActive = $this->evaluate($this->isActive(), $rowAsCollection);

			$label = $control->label();
			if (($newLabel = $this->evaluate($label, $rowAsCollection)) !== $label)
			{
				$control = $control->changeLabel($newLabel);
			}

			$view = $control->view();
			if (($newView = $this->evaluate($view, $rowAsCollection)) !== $view)
			{
				$control = $control->changeView($newView);
			}

			$options = $control->options();
			foreach ($options as $key => $option)
			{
				if ($option instanceof \Closure)
				{
					$control = $control->changeOption($key, $option($rowAsCollection));
				}
			}

			return $this->renderTarget($control, $target, $icon, $isActive);
		}
		catch (PermissionException $e)
		{
			return '';
		}
	}

	protected function renderTarget(ControlInterface $control, $target, $icon, $isActive)
	{
		if ($target === false)
		{
			return '';
		}

		return $control->render()->with([
			'target'   => $target,
			'icon'     => $icon,
			'isActive' => $isActive
		]);
	}
}

<?php namespace Digbang\L4Backoffice\Inputs;
use Digbang\L4Backoffice\Controls\ControlInterface;

/**
 * Class Input
 * @package Digbang\L4Backoffice\Inputs
 */
class Input implements InputInterface
{
	/**
	 * @var ControlInterface
	 */
	protected $control;
	protected $name;
	protected $value;
	protected $defaultsTo;
	protected $readonly = false;

	function __construct(ControlInterface $control, $name, $value = null)
	{
		$this->control = $control;
		$this->setName($name);
		$this->setValue($name, $value);

		if (! $control->options('id'))
		{
			$control->options()->put('id', $name);
		}
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function name()
	{
		return $this->name;
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
	 * @param string $name
	 * @param mixed $value
	 */
	public function setValue($name, $value)
	{
		if ($this->hasName($name))
		{
			$this->value = $value;
		}
	}

	/**
	 * @return mixed
	 */
	public function value()
	{
		return $this->value;
	}

	public function defaultsTo($value)
	{
		$this->defaultsTo = $value;
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

	public function setReadonly()
	{
		$this->readonly = true;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return \Illuminate\View\View
	 */
	public function render()
	{
		return $this->control->render()
			->with([
				'name'    =>  $this->name(),
				'value'   =>  $this->value() !== null ? $this->value() : $this->defaultsTo,
				'readonly' => $this->readonly
			]);
	}

	/**
	 * Check if the input matches the given name
	 *
	 * @param $name
	 * @return boolean
	 */
	public function hasName($name)
	{
		return $this->name == $name;
	}
}
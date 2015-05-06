<?php namespace Digbang\L4Backoffice\Inputs;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Controls\ControlWrapperTrait;

/**
 * Class Input
 * @package Digbang\L4Backoffice\Inputs
 */
class Input implements InputInterface
{
	use ControlWrapperTrait;

	/**
	 * @type string
	 */
	protected $name;

	/**
	 * @type mixed
	 */
	protected $value;

	/**
	 * @type mixed
	 */
	protected $defaultsTo;

	/**
	 * @type bool
	 */
	protected $readonly = false;

	/**
	 * @type bool
	 */
	protected $visible = true;

	/**
	 * @param ControlInterface $control
	 * @param string           $name
	 * @param mixed            $value
	 */
	public function __construct(ControlInterface $control, $name, $value = null)
	{
		$this->setName($name);
		$this->setValue($name, $value);

		if (! $control->option('id'))
		{
			$control = $control->changeOption('id', $name);
		}

		$this->control = $control;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->name;
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

	public function hide()
	{
		$this->visible = false;
	}

	public function isVisible()
	{
		return $this->visible;
	}
}

<?php namespace Digbang\L4Backoffice\Inputs;
use Digbang\L4Backoffice\Controls\ControlInterface;

/**
 * Class Composite
 * @package Digbang\L4Backoffice\Inputs
 */
class Composite implements InputInterface
{
	protected $inputCollection;
	protected $control;
	protected $name;

	function __construct($name, ControlInterface $control, Collection $inputCollection)
	{
		$this->name = $name;
		$this->control = $control;
		$this->inputCollection = $inputCollection;
	}

	/**
	 * A text that will be printed to the user.
	 * @return string
	 */
	public function label()
	{
		return $this->inputCollection->all()->lists('label', 'name');
	}

	/**
	 * The control HTML options. May access a specific one through parameter, null should return all of them.
	 *
	 * @param string $name
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function options($name = null)
	{
		if ($name)
		{
			return $this->inputCollection->all()->lists("options.$name", 'name');
		}

		return $this->inputCollection->all()->lists('options', 'name');
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
	 * The input name, as will be sent in the request.
	 * @return string
	 */
	public function name()
	{
		return $this->inputCollection->all()->lists('name');
	}

	/**
	 * The input value as received from the user.
	 * @return string It may well be an integer, but everything is stringy from HTTP requests...
	 */
	public function value()
	{
		return $this->inputCollection->all()->lists('value', 'name');
	}

	/**
	 * The input default value, used before the user interacts.
	 *
	 * @param $value
	 */
	public function defaultsTo($value)
	{
		return $this->inputCollection->all()->lists('defaultsTo', 'name');
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return $this->control->render()->with([
			'inputs' => $this->inputCollection
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
		return ! is_null($this->inputCollection->find($name));
	}

	/**
	 * Sets the value of the input.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function setValue($name, $value)
	{
		if ($input = $this->inputCollection->find($name))
		{
			/* @var $input InputInterface */
			$input->setValue($name, $value);
		}
	}
}

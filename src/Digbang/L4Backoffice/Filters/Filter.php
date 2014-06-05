<?php namespace Digbang\L4Backoffice\Filters;
use Digbang\L4Backoffice\Controls\ControlInterface;

/**
 * Class Filter
 * @package Digbang\L4Backoffice\Filters
 */
class Filter implements FilterInterface
{
	/**
	 * @var ControlInterface
	 */
	protected $control;
	protected $name;
	protected $value;
	protected $defaultsTo;

	function __construct(ControlInterface $control, $name, $value = null)
	{
		$this->control = $control;
		$this->name = $name;
		$this->value = $value;
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
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
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

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return \Illuminate\View\View
	 */
	public function render()
	{
		return $this->control->render()
			->with([
				'name'    => $this->name(),
				'value'   => $this->value(),
				'default' => $this->defaultsTo
			]);
	}
}
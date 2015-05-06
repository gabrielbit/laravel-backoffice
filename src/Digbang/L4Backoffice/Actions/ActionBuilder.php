<?php namespace Digbang\L4Backoffice\Actions;
use Illuminate\Support\Collection as LaravelCollection;
use Digbang\L4Backoffice\Support\EvaluatorTrait;

/**
 * Class ActionBuilder
 *
 * @package Digbang\L4Backoffice\Actions
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 * @method $this addDataToggle($message)
 * @method $this addDataPlacement($message)
 * @method $this addTitle($message)
 */
class ActionBuilder implements ActionBuilderInterface
{
	use EvaluatorTrait;

	/**
	 * @type ActionFactory
	 */
	private $factory;

	/**
	 * @type string
	 */
	private $target;

	/**
	 * @type string
	 */
	private $label;

	/**
	 * @type array
	 */
	private $options = [];

	/**
	 * @type string
	 */
	private $view;

	/**
	 * @type string
	 */
	private $icon;

	/**
	 * @param ActionFactory $factory
	 */
	public function __construct(ActionFactory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @param string $target
	 * @return $this
	 */
	public function to($target)
    {
	    $this->target = $target;

        return $this;
    }

	/**
	 * @param string $label
	 * @return $this
	 */
    public function labeled($label)
    {
	    $this->label = $label;

        return $this;
    }

	/**
	 * @param string $view
	 * @return $this
	 */
    public function view($view)
    {
	    $this->view = $view;

        return $this;
    }

	/**
	 * @param string $icon
	 * @return $this
	 */
	public function icon($icon)
    {
	    $this->icon = $icon;

        return $this;
    }

	/**
	 * @param string $attribute
	 * @param string $value
	 * @return $this
	 */
	public function add($attribute, $value)
	{
		if (isset($this->options[$attribute]))
		{
			$value = $this->getConcatenatedValue($this->options[$attribute], $value);
		}

		$this->options[$attribute] = $value;

		return $this;
	}

	protected function getConcatenatedValue($previous, $value)
	{
		if ($previous instanceof \Closure || $value instanceof \Closure)
		{
			return function(LaravelCollection $row) use ($previous, $value) {
				return $this->evaluate($previous, $row) . ' ' . $this->evaluate($value, $row);
			};
		}

		return "$previous $value";
	}

	/**
	 * @return Action
	 */
	public function asLink()
	{
		return $this->factory->link(
			$this->target,
			$this->label,
			$this->options,
			$this->view,
			$this->icon
		);
	}

	/**
	 * @param string $method
	 *
	 * @return Action
	 */
	public function asForm($method = 'POST')
	{
		return $this->factory->form(
			$this->target,
			$this->label,
			$method,
			$this->options,
			$this->view
		);
	}

	/**
	 * @param string $func
	 * @param array $args
	 *
	 * @return $this
	 * @throws \BadMethodCallException
	 */
	public function __call($func, $args)
	{
		if (strpos($func, 'add') === 0)
		{
			$addWhat = snake_case(substr($func, 3), '-');

			return $this->add($addWhat, array_shift($args));
		}

		throw new \BadMethodCallException("Method $func does not exist.");
	}
}

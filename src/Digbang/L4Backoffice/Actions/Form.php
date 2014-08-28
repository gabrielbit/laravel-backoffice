<?php namespace Digbang\L4Backoffice\Actions;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * Class Form
 * @package Digbang\L4Backoffice\Actions
 */
class Form extends Action implements ActionInterface
{
	protected $method;

	function __construct(ControlInterface $control, $target, $method = 'POST')
	{
		parent::__construct($control, $target);

		$this->method = $method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * @return string
	 */
	public function method()
	{
		return $this->method;
	}

	public function render()
	{
		return parent::render()->with([
			'method' => $this->method()
		]);
	}

	public function renderWith($row)
	{
		if ($view = parent::renderWith($row))
		{
			return $view->with([
				'method' => $this->method()
			]);
		}

		return $view;
	}
}
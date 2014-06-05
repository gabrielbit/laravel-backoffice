<?php namespace Digbang\L4Backoffice\Filters;
use Digbang\L4Backoffice\Controls\ControlInterface;

/**
 * Class DropDown
 * @package Digbang\L4Backoffice\Filters
 */
class DropDown extends Filter implements FilterInterface
{
	protected $data;

	function __construct(ControlInterface $control, $name, $value = null, $data = [])
	{
		parent::__construct($control, $name, $value);

		$this->setData($data);
	}


	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return mixed
	 */
	public function data()
	{
		return $this->data;
	}

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return \View::make($this->view(), [
			'name'    => $this->name(),
			'label'   => $this->label(),
			'data'    => $this->data(),
			'value'   => $this->value(),
			'options' => $this->options()->toArray()
		]);
	}
}
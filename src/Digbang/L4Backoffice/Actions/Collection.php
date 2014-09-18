<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Forms\Form as GenericForm;

class Collection implements \IteratorAggregate, \Countable
{
	protected $factory;
	protected $collection;

	function __construct(ActionFactory $factory, \Illuminate\Support\Collection $collection)
	{
		$this->factory = $factory;
		$this->collection = $collection;
	}

	public function link($target, $label = null, $options = [], $view = 'l4-backoffice::actions.link', $icon = null)
	{
		$this->collection->push($this->factory->link($target, $label, $options, $view, $icon));

		return $this;
	}

	public function form($target, $label, $method = 'POST', $options = [])
	{
		$this->collection->push($this->factory->form($target, $label, $method, $options));

		return $this;
	}

	/**
	 * @param string $label
	 * @param array  $options
	 * @param string $view
	 * @return Composite
	 */
	public function dropdown($label, $options = [], $view = null, $icon = null)
	{
		$this->collection->push($dropdown = $this->factory->dropdown($label, $options, $view, $icon));

		return $dropdown;
	}

	public function add(ActionInterface $action)
	{
		$this->collection->push($action);

		return $this;
	}

	public function modal(GenericForm $form, $label, $options = [], $icon = null)
	{
		$this->collection->push($this->factory->modal($form, $label, $options, $icon));

		return $this;
	}

	/**
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
	 */
	public function getIterator()
	{
		return $this->collection;
	}

	/**
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 */
	public function count()
	{
		return $this->collection->count();
	}
}

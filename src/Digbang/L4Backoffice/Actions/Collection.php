<?php namespace Digbang\L4Backoffice\Actions;

class Collection implements \IteratorAggregate, \Countable
{
	protected $factory;
	protected $collection;

	function __construct(ActionFactory $factory, \Illuminate\Support\Collection $collection)
	{
		$this->factory = $factory;
		$this->collection = $collection;
	}

	public function link($target, $label = null, $options = [])
	{
		$this->collection->push($this->factory->link($target, $label, $options));

		return $this;
	}

	public function form($target, $label, $method = 'POST', $options = [])
	{
		$this->collection->push($this->factory->form($target, $label, $method, $options));

		return $this;
	}

	/**
	 * @param $label
	 * @param array $options
	 * @return Composite
	 */
	public function dropdown($label, $options = [])
	{
		$this->collection->push($dropdown = $this->factory->dropdown($label, $options));

		return $dropdown;
	}

	public function add(ActionInterface $action)
	{
		$this->collection->push($action);

		return $this;
	}

	/**
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
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

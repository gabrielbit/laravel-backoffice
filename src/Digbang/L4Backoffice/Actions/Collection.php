<?php namespace Digbang\L4Backoffice\Actions;

use Traversable;

class Collection implements \IteratorAggregate
{
	protected $factory;
	protected $collection;

	function __construct(Factory $factory, \Illuminate\Support\Collection $collection)
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
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator()
	{
		return $this->collection;
	}
}

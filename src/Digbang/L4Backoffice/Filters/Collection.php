<?php namespace Digbang\L4Backoffice\Filters;

use Traversable;

class Collection implements \IteratorAggregate
{
	protected $collection;
	protected $factory;

	public function __construct(Factory $factory, \Illuminate\Support\Collection $collection)
	{
		$this->factory = $factory;
		$this->collection = $collection;
	}

	public function text($name, $label = null, $options = [])
	{
		return $this->add($this->factory->text($name, $label, $options));
	}

	public function dropdown($name, $label = null, $data = [], $options = [])
	{
		return $this->add($this->factory->dropdown($name, $label, $data, $options));
	}

	public function find($name)
	{
		return $this->collection->first(function($key, FilterInterface $filter) use ($name){
			return $filter->name() == $name;
		});
	}

	public function add(FilterInterface $filter)
	{
		$this->collection->push($filter);

		return $this;
	}

	public function __call($name, $args)
	{
		if (method_exists($this->collection, $name))
		{
			return call_user_func_array([$this->collection, $name], $args);
		}

		throw new \BadMethodCallException("Method $name not found.");
	}

	public function all()
	{
		return $this->collection;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
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

<?php namespace Digbang\L4Backoffice\Inputs;

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
		return $this->collection->first(function($key, InputInterface $input) use ($name){
			return $input->name() == $name;
		});
	}

	public function add(InputInterface $input)
	{
		$this->collection->push($input);

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
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator()
	{
		return $this->collection;
	}
}

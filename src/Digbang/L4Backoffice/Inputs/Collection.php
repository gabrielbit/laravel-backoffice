<?php namespace Digbang\L4Backoffice\Inputs;

use Doctrine\DBAL\Types\Type;

/**
 * Class Collection
 * @package Digbang\L4Backoffice\Inputs
 * @method \Digbang\L4Backoffice\Inputs\Collection date($name, $label, $options = [])
 * @method \Digbang\L4Backoffice\Inputs\Collection datetime($name, $label, $options = [])
 * @method \Digbang\L4Backoffice\Inputs\Collection time($name, $label, $options = [])
 */
class Collection implements \IteratorAggregate
{
	protected $collection;
	protected $factory;

	public function __construct(InputFactory $factory, \Illuminate\Support\Collection $collection)
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

	public function button($name, $label, $options = [])
	{
		return $this->add($this->factory->button($name, $label, $options));
	}

	public function checkbox($name, $label, $options = [])
	{
		return $this->add($this->factory->checkbox($name, $label, $options));
	}

	public function integer($name, $label, $options = [])
	{
		return $this->add($this->factory->integer($name, $label, $options));
	}

	public function boolean($name, $label, $options = [])
	{
		return $this->add($this->factory->dropdown($name, $label, [''=> '', 'true' => 'Yes', 'false' => 'No'], $options));
	}

	public function hidden($name, $options = [])
	{
		return $this->add($this->factory->hidden($name, $options));
	}

	public function composite($name, Collection $collection, $label = '', $options = [])
	{
		return $this->add($this->factory->composite($name, $collection, $label, $options));
	}

	public function find($name)
	{
		return $this->collection->first(function($key, InputInterface $input) use ($name){
			return $input->hasName($name);
		});
	}

	public function setValue($name, $value)
	{
		return $this->find($name)->setValue($name, $value);
	}

	public function add(InputInterface $input)
	{
		$this->collection->push($input);

		return $this;
	}

	public function __call($name, $args)
	{
		if (Type::hasType($name))
		{
			// Supported Doctrine type
			switch ($name)
			{
				case Type::TARRAY:
				case Type::SIMPLE_ARRAY:
				case Type::JSON_ARRAY:
					$func = 'dropdown';
					break;
				case Type::BIGINT:
				case Type::SMALLINT:
				case Type::FLOAT:
				case Type::DECIMAL:
					$func = 'integer';
					break;
				case Type::DATE:
					$func = 'date';
					break;
				case Type::DATETIME:
				case Type::DATETIMETZ:
					$func = 'datetime';
					break;
				case Type::TIME:
					$func = 'time';
					break;
				case Type::STRING:
				case Type::OBJECT:
				case Type::BLOB:
				case Type::GUID:
				default:
					$func = 'text';
			}

			return $this->add(call_user_func_array([$this->factory, $func], $args));
		}

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

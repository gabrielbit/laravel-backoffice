<?php namespace Digbang\L4Backoffice\Generator\Model;
use Illuminate\Support\Str;
use Doctrine\DBAL\Schema\Column;

/**
 * Class ColumnDecorator
 * @package Digbang\L4Backoffice\Generator\Model
 */
class ColumnDecorator implements \ArrayAccess
{
	protected $column;
	/**
	 * @var \Illuminate\Support\Str
	 * @mixin \Digbang\L4Backoffice\Support\Str
	 */
	protected $str;

	function __construct(Column $column, Str $str)
	{
		$this->column = $column;
		$this->str = $str;
	}

	public function __toString()
	{
		return (string) $this->column->getName();
	}

	/**
	 * @return Column
	 */
	public function getColumn()
	{
		return $this->column;
	}

	public function getTitle()
	{
		return $this->str->titleFromSlug($this->column->getName());
	}

	public function getType()
	{
		return $this->str->camel($this->column->getType()->getName());
	}

	public function isType($type)
	{
		return $this->column->getType()->getName() == $type;
	}

	public function hasAnyTypes(array $types)
	{
		return in_array($this->column->getType()->getName(), $types);
	}

	public function offsetExists($offset)
	{
		$method = $this->getterMethodFor($offset);

		return method_exists($this, $method) || method_exists($this->column, $method);
	}

	public function offsetGet($offset)
	{
		$method = $this->getterMethodFor($offset);

		return $this->callGetterFor($method);
	}

	public function offsetSet($offset, $value)
	{
		$method = $this->setterMethodFor($offset);

		return $this->callSetterFor($value, $method);
	}

	public function offsetUnset($offset)
	{
		return $this->offsetSet($offset, null);
	}

	protected function getterMethodFor($offset)
	{
		return 'get' . $this->str->camel($offset);
	}

	protected function setterMethodFor($offset)
	{
		return 'set' . $this->str->camel($offset);
	}

	protected function callSetterFor($method, $value)
	{
		if (method_exists($this, $method))
		{
			return $this->{$method}($value);
		}

		return $this->column->{$method}($value);
	}

	protected function callGetterFor($method)
	{
		if (method_exists($this, $method))
		{
			return $this->{$method}();
		}

		return $this->column->{$method}();
	}
}
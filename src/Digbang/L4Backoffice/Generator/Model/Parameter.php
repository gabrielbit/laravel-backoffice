<?php namespace Digbang\L4Backoffice\Generator\Model;

class Parameter
{
	/**
	 * @type string
	 */
	private $type;

	/**
	 * @type string
	 */
	private $name;

	public function __construct($type, StrDecorator $name)
	{
		$this->type = $type;
		$this->name = $name;
	}

	public function type()
	{
		return $this->type;
	}

	public function name()
	{
		return $this->name;
	}

	public function input()
	{
		switch ($this->type)
		{
			case 'array':
				return 'dropdown';
			case 'boolean':
			case 'bool':
				return 'checkbox';
			case 'int':
			case 'integer':
			case 'float':
			case 'double':
				return 'number';
			case 'string':
			case 'mixed':
			default:
				return 'text';
		}
	}
}

<?php namespace Digbang\L4Backoffice\Generator\Model;

/**
 * Class ClassData
 * @package Digbang\L4Backoffice\Generator\Model
 */
class ClassData
{
	protected $tableName;

	function __construct($tableName)
	{
		$this->tableName = $tableName;
	}

	public function name()
	{
		return \Str::studly(\Str::singular($this->tableName));
	}

	public function snake()
	{
		return $this->tableName;
	}

	public function plural()
	{
		return \Str::plural($this->name());
	}

	public function camel()
	{
		return \Str::camel($this->tableName);
	}
} 
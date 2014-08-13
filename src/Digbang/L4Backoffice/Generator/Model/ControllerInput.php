<?php namespace Digbang\L4Backoffice\Generator\Model;

/**
 * Class ControllerInput
 * @package Digbang\L4Backoffice\Generator
 */
class ControllerInput
{
	protected $namespace;
	protected $tableName;
	protected $model;
	protected $columns;
	protected $classData;
	protected $inputs;

	function __construct($namespace, $tableName, $model, $columns)
	{
		$this->namespace   = $namespace;
		$this->tableName   = $tableName;
		$this->model       = $model;
		$this->columns     = $this->filterColumns($columns);
		$this->classData = new ClassData($tableName);
		$this->inputs = $this->makeInputs();
	}

	/**
	 * @return mixed
	 */
	public function columns()
	{
		return $this->columns;
	}

	public function inputs()
	{
		return $this->inputs;
	}

	/**
	 * @return mixed
	 */
	protected function makeInputs()
	{
		$singularId = \Str::singular($this->tableName) . '_id';

		// Mustache doesn't like lists with missing numeric indexes, so remake them with array_values
		return array_values(array_filter($this->columns, function($column) use ($singularId) {
			return
				$column['name'] != 'id' &&
				$column['name'] != $singularId;
		}));
	}

	/**
	 * @return mixed
	 */
	public function model()
	{
		return $this->model;
	}

	/**
	 * @return mixed
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @return mixed
	 */
	public function tableName()
	{
		return $this->tableName;
	}

	/**
	 * @return mixed
	 */
	public function titleGetter()
	{
		return array_first($this->columns, function($key, $value){
			return $value['name'] != 'id';
		})['name'];
	}

	public function columns_hide()
	{
		if (array_first($this->columns, function($key, $value){
			return $value['name'] == 'id';
		}))
		{
			return "'id'";
		}
	}

	public function classData()
	{
		return $this->classData;
	}

	protected function filterColumns($columns)
	{
		return $this->nameTitleColumns(array_filter($columns, function($column){
			return
				$column != 'created_at' &&
				$column != 'updated_at' &&
				$column != 'deleted_at';
		}));
	}

	protected function nameTitleColumns($columns)
	{
		return array_map(function ($column) {
			return [
				'name'  => $column,
				'title' => \Str::titleFromSlug($column)
			];
		}, $columns);
	}
}
<?php namespace Digbang\L4Backoffice\Generator\Model;
use Digbang\L4Backoffice\Support\Collection;
use Doctrine\DBAL\Types\Type;

/**
 * Class ControllerInput
 * @package Digbang\L4Backoffice\Generator
 */
class ControllerInput
{
	protected $namespace;
	protected $tableName;
	protected $model;
	/**
	 * @var \Digbang\L4Backoffice\Support\Collection
	 */
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
		return $this->columns->values();
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

		// Mustache doesn't like lists with missing numeric indexes, so always return collection->values()
		return $this->columns->filter(function($column) use ($singularId) {
			return
				$column['name'] != 'id' &&
				$column['name'] != $singularId;
		})->values();
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
		$firstString = $this->columns->first(function ($key, ColumnDecorator $columnDecorator) {
			$column = $columnDecorator->getColumn();
			return
				$column->getName() != 'id' &&
				$columnDecorator->hasAnyTypes([Type::STRING, Type::TEXT]);
		});

		if ($firstString)
		{
			return $firstString['name'];
		}

		$firstNonId = $this->columns->first(function ($key, ColumnDecorator $value) {
			$column = $value->getColumn();
			return $column->getName() != 'id';
		});

		return $firstNonId['name'];
	}

	public function columns_hide()
	{
		if ($this->columns->first(function($key, ColumnDecorator $value){
			$column = $value->getColumn();
			return $column->getName() == 'id';
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
		$columns = new Collection($columns);

		return $columns->filter(function($column){
			return
				$column != 'created_at' &&
				$column != 'updated_at' &&
				$column != 'deleted_at';
		});
	}
}
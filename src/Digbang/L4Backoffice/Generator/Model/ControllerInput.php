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
	protected $entityNamespace;
	protected $model;
	/**
	 * @var \Digbang\L4Backoffice\Support\Collection
	 */
	protected $columns;
	protected $classData;
	/**
	 * @var \Digbang\L4Backoffice\Support\Collection
	 */
	protected $inputs;
	protected $dependencies;

	function __construct($namespace, $tableName, $entityNamespace, $model, $columns, array $dependencies = [])
	{
		$this->namespace       = $namespace;
		$this->tableName       = $tableName;
		$this->entityNamespace = $entityNamespace;
		$this->model           = $model;
		$this->columns         = $this->filterColumns($columns);
		$this->classData       = new ClassData($tableName);
		$this->inputs          = $this->makeInputs();
		$this->dependencies    = $dependencies;
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
		return $this->inputs->filter(function(ColumnDecorator $columnDecorator){
			return !array_key_exists($columnDecorator->getColumn()->getName(), $this->dependencies);
		})->values();
	}

	public function hasRequiredFields()
	{
		return !empty($this->requiredFields());
	}

	public function requiredFields()
	{
		return $this->inputs->filter(function(ColumnDecorator $columnDecorator){
			$column = $columnDecorator->getColumn();

			return $column->getNotnull() && !$column->getDefault();
		})->values();
	}

	public function entityNamespace()
	{
		return $this->entityNamespace;
	}

	public function dependencies()
	{
		$dependencies = [];

		foreach ($this->dependencies as $foreignKey => $data)
		{
			$table = $data['table'];

			if ($table != $this->tableName)
			{
				$titleGetter = $this->bestTitleForColumns($data['columns']);

				$dependencies[] = [
					'studlyCase' => \Str::singular(\Str::studly($table)),
					'title' => \Str::singular(\Str::titleFromSlug(str_replace('_id', '', $foreignKey))),
					'camelCase' => \Str::camel($table),
					'singular' => \Str::singular(\Str::camel($table)),
					'titleGetter' => $titleGetter,
					'column' => $foreignKey
				];
			}
		}

		return $dependencies;
	}

	public function hasDependencies()
	{
		return !empty($this->dependencies());
	}

	public function uniqueDependencies()
	{
		$unique = [];

		$dependencies = new Collection($this->dependencies());

		$tables = $dependencies->map(function($value){
			return $value['studlyCase'];
		})->unique();

		foreach ($tables as $table)
		{
			$unique[] = $dependencies->first(function($key, $value) use ($table){
				return $value['studlyCase'] == $table;
			});
		}

		return $unique;
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
		return $this->bestTitleForColumns($this->columns);
	}

	public function hiddenColumns()
	{
		return $this->columns->filter(function(ColumnDecorator $columnDecorator){
			$name = $columnDecorator->getColumn()->getName();

			return $name == 'id' || array_key_exists($name, $this->dependencies);
		})->values();
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

	protected function bestTitleForColumns(Collection $columns)
	{
		$firstString = $columns->first(function ($key, ColumnDecorator $columnDecorator)
		{
			$column = $columnDecorator->getColumn();

			return
				$column->getName() != 'id' &&
				$columnDecorator->hasAnyTypes([Type::STRING, Type::TEXT]);
		});

		if ($firstString)
		{
			return $firstString['name'];
		}

		$firstNonId = $columns->first(function ($key, ColumnDecorator $value)
		{
			$column = $value->getColumn();

			return $column->getName() != 'id';
		});

		return $firstNonId['name'];
	}
}